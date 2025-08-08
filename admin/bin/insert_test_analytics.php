<?php
// Script to insert test analytics data
require_once("../../system/database.php");

try {
    // Ensure analytics table exists
    $sql = "CREATE TABLE IF NOT EXISTS analytics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255),
        ip_address VARCHAR(45),
        user_agent TEXT,
        country_code VARCHAR(10),
        referer_url TEXT,
        browser VARCHAR(50),
        is_mobile TINYINT(1) DEFAULT 0,
        session_start DATETIME,
        visit_time DATETIME,
        page_url VARCHAR(500),
        page_views INT DEFAULT 1,
        bounced TINYINT(1) DEFAULT 0,
        session_duration INT DEFAULT 0,
        INDEX idx_ip_useragent (ip_address, user_agent),
        INDEX idx_visit_time (visit_time),
        INDEX idx_page_url (page_url)
    )";
    
    $pdo->exec($sql);
    echo "✓ Analytics table created/verified<br>";
    
    // Insert test data
    $testPages = [
        '/index.php' => 150,
        '/about.php' => 75,
        '/contact.php' => 60,
        '/products.php' => 45,
        '/services.php' => 30,
        '/blog.php' => 25,
        '/faq.php' => 20,
        '/privacy.php' => 15,
        '/terms.php' => 10,
        '/sitemap.php' => 5
    ];
    
    $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
    $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ];
    
    $insertCount = 0;
    
    foreach ($testPages as $pageUrl => $visits) {
        for ($i = 0; $i < $visits; $i++) {
            $sessionId = 'test_session_' . rand(1000, 9999);
            $ipAddress = '192.168.1.' . rand(1, 254);
            $userAgent = $userAgents[array_rand($userAgents)];
            $browser = $browsers[array_rand($browsers)];
            $isMobile = rand(0, 1);
            $bounced = rand(0, 1);
            $pageViews = rand(1, 5);
            $sessionDuration = rand(30, 1800);
            
            // Random date within last 30 days
            $visitTime = date('Y-m-d H:i:s', time() - rand(0, 30 * 24 * 60 * 60));
            $sessionStart = date('Y-m-d H:i:s', strtotime($visitTime) - $sessionDuration);
            
            $stmt = $pdo->prepare("INSERT INTO analytics (session_id, ip_address, user_agent, country_code, referer_url, browser, is_mobile, session_start, visit_time, page_url, page_views, bounced, session_duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $sessionId,
                $ipAddress,
                $userAgent,
                'NL',
                'https://google.com',
                $browser,
                $isMobile,
                $sessionStart,
                $visitTime,
                $pageUrl,
                $pageViews,
                $bounced,
                $sessionDuration
            ]);
            
            $insertCount++;
        }
    }
    
    echo "✓ Inserted $insertCount test records<br>";
    echo "<p>Test data has been inserted. You can now check the analytics dashboard to see if the 'Top 10 Populairste Pagina's' is working correctly.</p>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
