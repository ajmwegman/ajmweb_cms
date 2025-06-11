<?php
session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

// Variabelen
$quality = 75;  // Compressiekwaliteit
$max_width = 1920;  // Maximale breedte voor resizen
$max_height = 1080; // Maximale hoogte voor resizen
$batch_size = 5;  // Aantal bestanden per cron run

// Haal de bestaande PDO-verbinding uit database.php
require_once("../system/database.php");
require_once("../src/database.class.php");

// Functie om afbeeldingen te resizen en comprimeren
function resizeImage($source, $destination, $max_width, $max_height, $quality) {
    $info = getimagesize($source);
    list($width, $height) = $info;

    // Bereken de nieuwe afmetingen met behoud van aspect ratio
    $ratio = $width / $height;
    if ($width > $max_width || $height > $max_height) {
        if ($ratio > 1) {
            $new_width = $max_width;
            $new_height = $max_width / $ratio;
        } else {
            $new_height = $max_height;
            $new_width = $max_height * $ratio;
        }
    } else {
        // Afbeelding is al kleiner dan 1920x1080, dus geen resizing nodig
        $new_width = $width;
        $new_height = $height;
    }

    // Afbeelding aanmaken afhankelijk van het type
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } else {
        return false;
    }

    // Nieuw formaat afbeelding maken
    $new_image = imagecreatetruecolor($new_width, $new_height);

    // Transparantie behouden voor PNG's en GIF's
    if ($info['mime'] == 'image/png' || $info['mime'] == 'image/gif') {
        imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    }

    // Resizen van de afbeelding
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Afbeelding opslaan
    if ($info['mime'] == 'image/jpeg') {
        imagejpeg($new_image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        imagepng($new_image, $destination, $quality / 10); // PNG compressie van 0-9
    } elseif ($info['mime'] == 'image/gif') {
        imagegif($new_image, $destination);
    }

    return true;
}

// Selecteer 5 bestanden met de status 'pending'
$stmt = $pdo->prepare("SELECT * FROM compressed_images WHERE status = 'pending' LIMIT :batch_size");
$stmt->bindValue(':batch_size', $batch_size, PDO::PARAM_INT);
$stmt->execute();
$files = $stmt->fetchAll(PDO::FETCH_OBJ);

// Verwerk de bestanden
foreach ($files as $file) {
    $filePath = $file->file_path;

    // Overschrijf het originele bestand na resizen en compressie
    if (resizeImage($filePath, $filePath, $max_width, $max_height, $quality)) {
        echo "Successfully compressed: " . $file->file_name . "\n";
        
        // Bereken de nieuwe bestandsgrootte
        $compressedSize = filesize($filePath);

        // Update de status en de bestandsgrootte in de database
        $stmt = $pdo->prepare("
            UPDATE compressed_images 
            SET status = 'success', compressed_at = NOW(), message = 'Compression successful', compressed_size = :compressed_size 
            WHERE id = :id
        ");
        $stmt->execute([
            'compressed_size' => $compressedSize,
            'id' => $file->id
        ]);
    } else {
        echo "Failed to compress: " . $file->file_name . "\n";
        
        // Als de compressie mislukt, update de status en zet de foutmelding
        $stmt = $pdo->prepare("
            UPDATE compressed_images 
            SET status = 'error', message = 'Compression failed' 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $file->id]);
    }
}
?>
