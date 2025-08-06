<?php
@session_start();

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

$path = $_SERVER['DOCUMENT_ROOT'];

if(!isset($db)) { 
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    $db = new database($pdo); 
}

// Error globals
$item = "Upload een afbeelding.";
$errormessage = '<strong> Let op! We hebben nog een paar dingen die niet in orde zijn!</strong>';

if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
}

if (!empty($hash)) {
    $location = "/photoslider/"; // Relative path where images will be stored
    $absoluteLocation = $path . $location; // Absolute path for folder creation

    // Maak de folder aan als deze nog niet bestaat
    if (!is_dir($absoluteLocation)) {
        mkdir($absoluteLocation, 0777, true); // Create the directory with correct permissions
    }

    $sql = "SELECT id, hash, image FROM group_photoslider WHERE hash = ?";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute([$hash]);
    $row = $stmt->fetch();

    if (!empty($row)) {
        $image = $row['image'];

        if (!empty($image)) {
            // Toon de afbeelding
            echo '<p><img src="' . $location . $row['image'] . '" alt="" class="img-fluid"></p>';
?>
            <!-- Delete Button for the Image -->
            <button value="<?php echo $row['hash']; ?>" 
                data-message="Weet je zeker dat je <?php echo $row['image']; ?> wilt verwijderen?" 
                id="btn<?php echo $row['id']; ?>"
                class="btn btn-danger btn-sm btn-ok mt-1" 
                data-bs-toggle="modal" 
                data-bs-target="#dialogModal">
                <i class="bi bi-trash" data-set="<?php echo $row['hash']; ?>"></i>
            </button>
<?php
        } else {
            echo $item;
        }
    }
} else {
    echo '<div class="alert alert-danger" role="alert">Geen geldige hash opgegeven!</div>';
}
?>
