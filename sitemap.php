<?php
require_once __DIR__ . '/system/database.php';

$baseUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/';

$urls = [];
// Active pages
try {
    $stmt = $pdo->query("SELECT seo_url FROM group_content WHERE status = 'y'");
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
        if (!empty($path)) {
            $urls[] = $baseUrl . ltrim($path, '/');
        }
    }

    // Active products
    $stmt = $pdo->query("SELECT seoTitle FROM group_products WHERE status = 'y'");
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
        if (!empty($path)) {
            $urls[] = $baseUrl . 'product/' . ltrim($path, '/');
        }
    }
} catch (Exception $e) {
    echo 'Database error: ' . $e->getMessage();
    exit(1);
}

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');
foreach ($urls as $url) {
    $u = $xml->addChild('url');
    $u->addChild('loc', htmlspecialchars($url));
}

file_put_contents(__DIR__ . '/sitemap.xml', $xml->asXML());

echo "Generated sitemap.xml with " . count($urls) . " URLs\n";
?>
