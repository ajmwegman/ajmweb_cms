<?php
session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

// Variabelen
$map    = '/product_images/2018/';  // De hoofdmap die je wilt indexeren (bijv. 2018)
$root   = $_SERVER['DOCUMENT_ROOT'];
$directory = $root . $map; // Combineer document root met het relatieve pad

// Haal de bestaande PDO-verbinding uit database.php
require_once("../system/database.php");
require_once("../src/database.class.php");

// Functie om de map en submappen recursief te indexeren
function indexDirectory($directory, $pdo) {
    $files = scandir($directory);
    foreach ($files as $file) {
        $filePath = $directory . $file;

        // Als het een map is (en niet . of ..), dan recursief door die map heen
        if (is_dir($filePath) && $file != '.' && $file != '..') {
            // Recursief de submap indexeren
            indexDirectory($filePath . '/', $pdo);
        }

        // Als het een bestand is, dan opslaan in de database
        if (is_file($filePath) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
            $lastModified = date("Y-m-d H:i:s", filemtime($filePath));
            $originalSize = filesize($filePath); // Grootte in bytes

            // Gebruik 'INSERT ON DUPLICATE KEY UPDATE' om duplicaten te vermijden en gewijzigde bestanden bij te werken
            $stmt = $pdo->prepare("
                INSERT INTO compressed_images (file_name, file_path, last_modified, original_size, status) 
                VALUES (:file_name, :file_path, :last_modified, :original_size, 'pending')
                ON DUPLICATE KEY UPDATE 
                    last_modified = VALUES(last_modified),
                    original_size = VALUES(original_size),
                    status = IF(last_modified != VALUES(last_modified), 'pending', status)
            ");
            $stmt->execute([
                'file_name' => $file,
                'file_path' => $filePath,
                'last_modified' => $lastModified,
                'original_size' => $originalSize
            ]);

            echo "Indexed or updated file: $file\n";
        }
    }
}

// Roep de functie aan om de map en submappen te indexeren
indexDirectory($directory, $pdo);
?>
