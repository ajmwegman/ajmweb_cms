<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1); 

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");
require_once($path . "/admin/modules/photoslider/src/photoslider.class.php");

$db = new database($pdo);
$photoslider = new photoslider($pdo);

$hash = (isset($_POST['hash'])) ? $_POST['hash'] : "";

// Stel bestandslocaties in
$output_dir = $_SERVER['DOCUMENT_ROOT'] . "/temp/";
$location = $_SERVER['DOCUMENT_ROOT'] . "/photoslider/";

// Stel resize dimensies in
$resize_width = 640;
$resize_height = 480;

// Controleer of de mappen bestaan, maak anders aan
if (!is_dir($output_dir) && !mkdir($output_dir, 0777, true)) {
    die(json_encode(['error' => 'Kon de tijdelijke map niet aanmaken.']));
}

if (!is_dir($location) && !mkdir($location, 0777, true)) {
    die(json_encode(['error' => 'Kon de photoslider map niet aanmaken.']));
}

// Verwerk het bestand als het ontvangen is
if (isset($_FILES['myfile'])) {
    $file = $_FILES['myfile'];
    $fileName = basename($file['name']);
    $fileTmpPath = $file['tmp_name'];
    $fileError = $file['error'];

    // Controleer op uploadfouten
    if ($fileError !== UPLOAD_ERR_OK) {
        die(json_encode(['error' => 'Upload fout: ' . $fileError]));
    }

    // Controleer het bestandstype
    $allowed = ['jpg', 'jpeg', 'pjpeg', 'gif', 'png'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowed)) {
        die(json_encode(['error' => 'Ongeldig bestandstype. Alleen JPG, JPEG, GIF, en PNG zijn toegestaan.']));
    }

    // Verplaats het bestand naar de tijdelijke map
    if (!move_uploaded_file($fileTmpPath, $output_dir . $fileName)) {
        die(json_encode(['error' => 'Het verplaatsen van het bestand is mislukt.']));
    }

    // Verklein de afbeelding
    if (!resize_image($output_dir . $fileName, $location . $fileName, $resize_width, $resize_height)) {
        die(json_encode(['error' => 'Kon de afbeelding niet verkleinen.']));
    }

    // Verwijder de tijdelijke afbeelding
    if (file_exists($output_dir . $fileName)) {
        unlink($output_dir . $fileName);
    }

    // Selecteer filename om bestaande afbeelding op de server te verwijderen
    $row = $photoslider->getImage("hash", $hash);

    $image = $row['image'];

    // Verwijder bestaande afbeelding als deze aanwezig is
    if (!empty($image)) {
        $filename = $location . $image;

        $sql = "UPDATE group_photoslider SET image=:image WHERE hash=:hash";
        $go = $db->runQuery($sql, ['image' => '', 'hash' => $hash]);

        if (file_exists($filename)) {
            @unlink($filename);
        }
    }

    // Update de database met de nieuwe bestandsnaam
    $sql = "UPDATE group_photoslider SET image=:image WHERE hash=:hash";
    $go = $db->runQuery($sql, ['image' => $fileName, 'hash' => $hash]);

    // Succesvolle upload
    echo json_encode(['success' => 'Bestand succesvol geüpload: ' . $fileName]);
    
    $json_generator = $photoslider->generate_all_sliders_json();
} else {
    echo json_encode(['error' => 'Geen bestand ontvangen voor upload.']);
}

// Resize functie voor afbeeldingen
function resize_image($source, $destination, $width, $height) {
    list($original_width, $original_height) = getimagesize($source);
    $image_p = imagecreatetruecolor($width, $height);

    // Afbeeldingstype controleren en de afbeelding creëren vanuit het bestand
    $image = null;
    $fileExt = strtolower(pathinfo($source, PATHINFO_EXTENSION));

    switch ($fileExt) {
        case 'jpg':
        case 'jpeg':
        case 'pjpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'png':
            $image = imagecreatefrompng($source);
            // Instellen van transparantie behoud voor PNG
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);
            break;
        case 'gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    // Verklein de afbeelding
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $original_width, $original_height);

    // Sla de afbeelding op in het opgegeven formaat
    switch ($fileExt) {
        case 'jpg':
        case 'jpeg':
        case 'pjpeg':
            imagejpeg($image_p, $destination, 90);
            break;
        case 'png':
            imagepng($image_p, $destination);
            break;
        case 'gif':
            imagegif($image_p, $destination);
            break;
    }

    // Vrijgeven van geheugen
    imagedestroy($image_p);
    imagedestroy($image);

    return true;
}
?>
