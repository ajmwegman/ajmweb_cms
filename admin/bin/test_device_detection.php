<?php
/**
 * Test Device Detection Script
 * Test verschillende user agents om device detection te controleren
 */

echo "<h2>Device Detection Test</h2>";
echo "<p>Tijd: " . date('Y-m-d H:i:s') . "</p>";

// Test user agents
$testUserAgents = [
    'Desktop Chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Desktop Firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
    'Desktop Safari' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
    'iPhone' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
    'Android Phone' => 'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36',
    'iPad' => 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
    'Android Tablet' => 'Mozilla/5.0 (Linux; Android 11; SM-T860) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Safari/537.36',
    'Kindle' => 'Mozilla/5.0 (Linux; U; Android 4.0.3; en-us; KFTT Build/IML74K) AppleWebKit/534.31 (KHTML, like Gecko) Version/4.0 Safari/534.31',
    'BlackBerry' => 'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.74 Mobile Safari/534.11+'
];

echo "<h3>Device Detection Test Resultaten:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Device Type</th><th>User Agent</th><th>Is Mobile</th><th>Is Tablet</th><th>Detected Device</th></tr>";

foreach ($testUserAgents as $deviceType => $userAgent) {
    $isMobile = false;
    $isTablet = false;
    
    // Tablet detectie
    if (preg_match('/iPad|Android.*Tablet|PlayBook|Silk|Kindle/', $userAgent)) {
        $isTablet = true;
        $isMobile = true;
    }
    // Mobile detectie (exclusief tablets)
    elseif (preg_match('/Mobile|Android|iPhone|BlackBerry|Windows Phone/', $userAgent)) {
        $isMobile = true;
    }
    
    // Bepaal detected device
    $detectedDevice = 'Desktop';
    if ($isTablet) {
        $detectedDevice = 'Tablet';
    } elseif ($isMobile) {
        $detectedDevice = 'Mobile';
    }
    
    echo "<tr>";
    echo "<td><strong>$deviceType</strong></td>";
    echo "<td style='font-size: 0.8rem; max-width: 400px; word-wrap: break-word;'>" . htmlspecialchars($userAgent) . "</td>";
    echo "<td>" . ($isMobile ? '✅ Ja' : '❌ Nee') . "</td>";
    echo "<td>" . ($isTablet ? '✅ Ja' : '❌ Nee') . "</td>";
    echo "<td><span style='color: " . ($detectedDevice === 'Desktop' ? '#49b5e7' : ($detectedDevice === 'Mobile' ? '#16df7e' : '#ffc107')) . "; font-weight: bold;'>$detectedDevice</span></td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>SQL Query Test:</h3>";

// Test de SQL query die we gebruiken
$testQuery = "
SELECT 
    CASE 
        WHEN is_mobile = 1 AND (
            user_agent LIKE '%iPad%' OR 
            user_agent LIKE '%Android%' AND user_agent LIKE '%Tablet%' OR
            user_agent LIKE '%PlayBook%' OR
            user_agent LIKE '%Silk%' OR
            user_agent LIKE '%Kindle%'
        ) THEN 'Tablet'
        WHEN is_mobile = 1 THEN 'Mobile'
        ELSE 'Desktop'
    END as device
FROM (SELECT 1 as is_mobile, user_agent FROM (
    SELECT 'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15' as user_agent
    UNION ALL
    SELECT 'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36' as user_agent
    UNION ALL
    SELECT 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' as user_agent
) as test_data) as analytics_test
";

echo "<p><strong>Test Query:</strong></p>";
echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($testQuery) . "</pre>";

echo "<hr>";
echo "<p><strong>Volgende stappen:</strong></p>";
echo "<ol>";
echo "<li>Controleer of alle devices correct worden gedetecteerd</li>";
echo "<li>Test met echte bezoekers data</li>";
echo "<li>Verfijn de detection rules indien nodig</li>";
echo "</ol>";
?> 