<?php
/**
 * Install Missing Table for Photoslider Module
 * 
 * This script creates the missing group_photoslider_names table
 * that is required for the photoslider module to function properly.
 */

// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");

try {
    // Create the missing table
    $sql = "CREATE TABLE IF NOT EXISTS `group_photoslider_names` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `name` (`name`)
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
    
    echo "✅ Photoslider module database setup complete!<br>";
    echo "<br><strong>You can now safely use the photoslider module.</strong><br>";
    echo "<br><a href='/admin/?module=photoslider' class='btn btn-primary'>Go to Photoslider Module</a>";
    
} catch (PDOException $e) {
    echo "❌ Error creating table: " . $e->getMessage();
    echo "<br><br>Please check your database permissions and try again.";
}
?>