<?php
/**
 * Install Missing Table for Photoslider Module
 * 
 * This script creates the missing database tables for the photoslider module.
 *
 * Tables created:
 * - group_photoslider_names
 * - group_photoslider_settings
 */

// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");

try {
    // Create the missing tables
    $sql = "CREATE TABLE IF NOT EXISTS `group_photoslider_names` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `name` (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $pdo->exec($sql);

    // Create settings table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS `group_photoslider_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `group_id` int(11) NOT NULL,
        `hash` varchar(255) NOT NULL,
        `height` int(11) DEFAULT 480,
        `speed` int(11) DEFAULT 4000,
        `buttons` varchar(1) DEFAULT 'y',
        `indicators` varchar(1) DEFAULT 'y',
        `folder` varchar(255) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `group_id` (`group_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $pdo->exec($sql);
    
    // Insert a default slider if none exists
    $checkSql = "SELECT COUNT(*) FROM group_photoslider_names";
    $count = $pdo->query($checkSql)->fetchColumn();

    if ($count == 0) {
        $insertSql = "INSERT INTO group_photoslider_names (name) VALUES ('Default Slider')";
        $pdo->exec($insertSql);
        echo "✅ Created group_photoslider_names table and added default slider.<br>";
    } else {
        echo "✅ Created group_photoslider_names table (already had data).<br>";
    }

    // Insert default settings if none exist
    $checkSql = "SELECT COUNT(*) FROM group_photoslider_settings";
    $count = $pdo->query($checkSql)->fetchColumn();

    if ($count == 0) {
        $hash = bin2hex(random_bytes(16));
        $insertSql = "INSERT INTO group_photoslider_settings (group_id, hash, height, speed, buttons, indicators, folder) VALUES (1, :hash, 480, 4000, 'y', 'y', '')";
        $stmt = $pdo->prepare($insertSql);
        $stmt->execute(['hash' => $hash]);
        echo "✅ Created group_photoslider_settings table and added default settings.<br>";
    } else {
        echo "✅ Created group_photoslider_settings table (already had data).<br>";
    }

    echo "✅ Photoslider module database setup complete!<br>";
    echo "<br><strong>You can now safely use the photoslider module.</strong><br>";
    echo "<br><a href='/admin/?module=photoslider' class='btn btn-primary'>Go to Photoslider Module</a>";
    
} catch (PDOException $e) {
    echo "❌ Error creating table: " . $e->getMessage();
    echo "<br><br>Please check your database permissions and try again.";
}
?>