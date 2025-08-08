<?php
// Database update script voor multi-site analytics ondersteuning
// Voegt site_id kolommen toe aan analytics tabellen
require_once("../../system/database.php");

try {
    echo "<h2>Analytics Multi-Site Database Update</h2>";
    
    // 1. Update analytics tabel
    echo "<h3>1. Analytics tabel bijwerken...</h3>";
    
    // Check if site_id column exists in analytics table
    $stmt = $pdo->prepare("SHOW COLUMNS FROM analytics LIKE 'site_id'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Add site_id column to analytics table
        $pdo->exec("ALTER TABLE analytics ADD COLUMN site_id INT DEFAULT 1 AFTER id");
        echo "<p style='color: green;'>✓ site_id kolom toegevoegd aan analytics tabel</p>";
        
        // Add index for site_id
        $pdo->exec("CREATE INDEX idx_site_id ON analytics(site_id)");
        echo "<p style='color: green;'>✓ Index toegevoegd voor site_id</p>";
    } else {
        echo "<p style='color: blue;'>✓ site_id kolom bestaat al in analytics tabel</p>";
    }
    
    // 2. Update analytics_aggregated tabel
    echo "<h3>2. Analytics Aggregated tabel bijwerken...</h3>";
    
    // Check if analytics_aggregated table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'analytics_aggregated'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Check if site_id column exists in analytics_aggregated table
        $stmt = $pdo->prepare("SHOW COLUMNS FROM analytics_aggregated LIKE 'site_id'");
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            // Add site_id column to analytics_aggregated table
            $pdo->exec("ALTER TABLE analytics_aggregated ADD COLUMN site_id INT DEFAULT 1 AFTER id");
            echo "<p style='color: green;'>✓ site_id kolom toegevoegd aan analytics_aggregated tabel</p>";
            
            // Add index for site_id
            $pdo->exec("CREATE INDEX idx_site_id_agg ON analytics_aggregated(site_id)");
            echo "<p style='color: green;'>✓ Index toegevoegd voor site_id in aggregated tabel</p>";
        } else {
            echo "<p style='color: blue;'>✓ site_id kolom bestaat al in analytics_aggregated tabel</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ analytics_aggregated tabel bestaat nog niet</p>";
    }
    
    // 3. Maak sites tabel aan als deze nog niet bestaat
    echo "<h3>3. Sites tabel controleren...</h3>";
    
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'sites'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Create sites table
        $pdo->exec("
            CREATE TABLE sites (
                id INT AUTO_INCREMENT PRIMARY KEY,
                domain VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_domain (domain),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "<p style='color: green;'>✓ Sites tabel aangemaakt</p>";
        
        // Insert default site
        $pdo->exec("
            INSERT INTO sites (domain, name, description) 
            VALUES ('default', 'Default Site', 'Default website for analytics')
        ");
        echo "<p style='color: green;'>✓ Default site toegevoegd</p>";
    } else {
        echo "<p style='color: blue;'>✓ Sites tabel bestaat al</p>";
    }
    
    // 4. Update bestaande records met default site_id
    echo "<h3>4. Bestaande records bijwerken...</h3>";
    
    // Update analytics records
    $stmt = $pdo->prepare("UPDATE analytics SET site_id = 1 WHERE site_id IS NULL OR site_id = 0");
    $stmt->execute();
    $updatedAnalytics = $stmt->rowCount();
    echo "<p style='color: green;'>✓ " . $updatedAnalytics . " analytics records bijgewerkt</p>";
    
    // Update analytics_aggregated records if table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'analytics_aggregated'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE analytics_aggregated SET site_id = 1 WHERE site_id IS NULL OR site_id = 0");
        $stmt->execute();
        $updatedAggregated = $stmt->rowCount();
        echo "<p style='color: green;'>✓ " . $updatedAggregated . " aggregated records bijgewerkt</p>";
    }
    
    // 5. Database optimalisatie
    echo "<h3>5. Database optimalisatie...</h3>";
    
    $stmt = $pdo->prepare("OPTIMIZE TABLE analytics");
    $stmt->execute();
    echo "<p style='color: green;'>✓ Analytics tabel geoptimaliseerd</p>";
    
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("OPTIMIZE TABLE analytics_aggregated");
        $stmt->execute();
        echo "<p style='color: green;'>✓ Analytics aggregated tabel geoptimaliseerd</p>";
    }
    
    $stmt = $pdo->prepare("OPTIMIZE TABLE sites");
    $stmt->execute();
    echo "<p style='color: green;'>✓ Sites tabel geoptimaliseerd</p>";
    
    // 6. Toon database status
    echo "<h3>6. Database Status:</h3>";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM analytics");
    $stmt->execute();
    $analyticsTotal = $stmt->fetch()['total'];
    echo "<p>Analytics records: " . $analyticsTotal . "</p>";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sites");
    $stmt->execute();
    $sitesTotal = $stmt->fetch()['total'];
    echo "<p>Sites: " . $sitesTotal . "</p>";
    
    // Toon tabel grootte
    $stmt = $pdo->prepare("
        SELECT 
            table_name,
            ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() 
        AND table_name IN ('analytics', 'analytics_aggregated', 'sites')
    ");
    $stmt->execute();
    $tableInfo = $stmt->fetchAll();
    
    echo "<h3>Tabel Grootte:</h3>";
    foreach ($tableInfo as $table) {
        echo "<p>" . $table['table_name'] . ": " . $table['Size (MB)'] . " MB</p>";
    }
    
    echo "<h3>Multi-Site Functionaliteit:</h3>";
    echo "<p>✓ Database is nu voorbereid voor multi-site analytics</p>";
    echo "<p>✓ Alle bestaande data is gemigreerd naar site_id = 1</p>";
    echo "<p>✓ Nieuwe analytics records zullen automatisch site_id krijgen</p>";
    
    echo "<h3>Volgende Stappen:</h3>";
    echo "<p>1. Voeg sites toe via de admin interface</p>";
    echo "<p>2. Update de analytics class om site_id te ondersteunen</p>";
    echo "<p>3. Voeg site selector toe aan de frontend</p>";
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>
