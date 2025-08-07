<?php
/**
 * Analytics Stats Update Script
 * Voegt geavanceerde analytics functionaliteit toe
 */

// Database verbinding
include("../../system/database.php");
require_once("../src/database.class.php");

echo "<h2>Analytics Stats Update Script</h2>";
echo "<p>Start tijd: " . date('Y-m-d H:i:s') . "</p>";

$errors = [];
$success = [];

try {
    // 1. Database aanpassingen
    echo "<h3>1. Database aanpassingen...</h3>";
    
    // Controleer of analytics tabel bestaat
    $stmt = $pdo->query("SHOW TABLES LIKE 'analytics'");
    if ($stmt->rowCount() == 0) {
        // Maak analytics tabel aan als deze niet bestaat
        $createTable = "
        CREATE TABLE `analytics` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `session_id` varchar(255) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `user_agent` text NOT NULL,
            `country_code` varchar(10) DEFAULT 'UNKNOWN',
            `referer_url` text DEFAULT NULL,
            `browser` varchar(50) DEFAULT 'unknown',
            `is_mobile` tinyint(1) DEFAULT 0,
            `page_views` int(11) DEFAULT 1,
            `session_duration` int(11) DEFAULT 0,
            `bounced` tinyint(1) DEFAULT 1,
            `session_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `visit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `page_url` varchar(255) DEFAULT NULL,
            `time_on_page` int(11) DEFAULT 0,
            `exit_page` varchar(255) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `auction_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `session_id` (`session_id`),
            KEY `ip_address` (`ip_address`),
            KEY `visit_time` (`visit_time`),
            KEY `browser` (`browser`),
            KEY `is_mobile` (`is_mobile`),
            KEY `bounced` (`bounced`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $pdo->exec($createTable);
        $success[] = "Analytics tabel aangemaakt";
    } else {
        $success[] = "Analytics tabel bestaat al";
    }
    
    // Voeg ontbrekende kolommen toe
    $columns = [
        'visit_time' => "ALTER TABLE analytics ADD COLUMN visit_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
        'page_url' => "ALTER TABLE analytics ADD COLUMN page_url VARCHAR(255) DEFAULT NULL",
        'time_on_page' => "ALTER TABLE analytics ADD COLUMN time_on_page INT(11) DEFAULT 0",
        'exit_page' => "ALTER TABLE analytics ADD COLUMN exit_page VARCHAR(255) DEFAULT NULL",
        'user_id' => "ALTER TABLE analytics ADD COLUMN user_id INT(11) DEFAULT NULL",
        'auction_id' => "ALTER TABLE analytics ADD COLUMN auction_id INT(11) DEFAULT NULL"
    ];
    
    foreach ($columns as $column => $sql) {
        try {
            // Controleer of kolom bestaat
            $stmt = $pdo->query("SHOW COLUMNS FROM analytics LIKE '$column'");
            if ($stmt->rowCount() == 0) {
                $pdo->exec($sql);
                $success[] = "Kolom '$column' toegevoegd";
            } else {
                $success[] = "Kolom '$column' bestaat al";
            }
        } catch (PDOException $e) {
            $errors[] = "Fout bij toevoegen kolom '$column': " . $e->getMessage();
        }
    }
    
    // Update bestaande records om visit_time te vullen
    $stmt = $pdo->query("UPDATE analytics SET visit_time = session_start WHERE visit_time = '0000-00-00 00:00:00' OR visit_time IS NULL");
    $updatedRows = $stmt->rowCount();
    if ($updatedRows > 0) {
        $success[] = "$updatedRows records bijgewerkt met visit_time";
    }
    
    echo "<h3>Database aanpassingen voltooid!</h3>";
    echo "<h4>Succesvol uitgevoerd:</h4>";
    echo "<ul>";
    foreach ($success as $msg) {
        echo "<li style='color: green;'>✓ $msg</li>";
    }
    echo "</ul>";
    
    if (!empty($errors)) {
        echo "<h4>Fouten:</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color: red;'>✗ $error</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Kritieke fout: " . $e->getMessage() . "</p>";
}

echo "<p>Eind tijd: " . date('Y-m-d H:i:s') . "</p>";
?> 