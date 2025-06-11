<?php
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];

if(!isset($db)) { 
    
    require_once( $path."/system/database.php" );
    require_once( $path."/admin/src/database.class.php" );

    $db = new database($pdo); 
} else {
    //echo "db";
}

if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
}

if (!empty($hash)) {

    $location = "/product_images/";

    $sql = "SELECT id, hash, image FROM group_product_images WHERE hash = ?";

    $stmt = $db->pdo->prepare($sql);
    $stmt->execute([$hash]);

    $rows = $stmt->fetchAll(); // Fetch all rows matching the hash

    $totalImages = count($rows);
    $imagesPerRow = 4;
    $currentImageIndex = 0;

    echo '<div class="row sortable-container">'; // Start een nieuwe rij

while ($currentImageIndex < $totalImages) {
    for ($i = 0; $i < $imagesPerRow && $currentImageIndex < $totalImages; $i++, $currentImageIndex++) {
        
        
                echo '<div class="row">'; // Start a new row

        for ($i = 0; $i < $imagesPerRow && $currentImageIndex < $totalImages; $i++, $currentImageIndex++) {
            $row = $rows[$currentImageIndex];
            $image = $row['image'];

            echo '<div class="col-md-3 block'.$row['id'].'">';
            if (!empty($image)) {
                echo '<p><img src="' . $location . $row['image'] . '" alt="" class="img-fluid"></p>';
?>
                <button value="<?php echo $row['hash']; ?>"
                        data-message="Weet je zeker dat je <?php echo $row['image']; ?> wilt verwijderen?"
                        id="btn<?php echo $row['id']; ?>"
                        class="btn btn-danger btn-sm btn-ok mt-1"
                        data-bs-toggle="modal"
                        data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo base64_encode($row['id']); ?>"></i>
                </button>
<?php
            } else {
                echo "";
            }
            echo '</div>';
        }

        echo '</div>'; // Close the row
        
    }
}

echo '</div>'; // Sluit de container

    
    
} // end hash
?>
