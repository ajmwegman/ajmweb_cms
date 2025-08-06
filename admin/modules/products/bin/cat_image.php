<?php 
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];
$location = "/product_images/";

if(!isset($db)) { 
    
    require_once( $path."/system/database.php" );
    require_once( $path."/admin/src/database.class.php" );
    require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/modules/products/src/products.class.php";

    $db = new database($pdo); 
    $products = new products($pdo);

    } else {
    //echo "db";
}

if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
}

echo '<div class="container card card-body">'; // Start een nieuwe container

if (!empty($hash)) {
    $images = $products->getImages($hash);
    echo '<div id="imageContainer" class="row gap-2">'; // Start een nieuwe container

    if ($images !== false) {
        foreach ($images as $row) { // Gebruik $row in plaats van $image
            $image = $row['image'];

            echo '<div id="imageBlock' . $row['id'] . '" class="col-md-2 block sortable-item" data-set="' . $row['id'] . '">';
            if (!empty($image)) {
                echo '<img src="' . $location . $image . '" alt="" class="img-fluid" data-rotation="0">';
                
                    // Knop voor linksom draaien
    echo '<button class="btn btn-secondary btn-sm rotate-left" data-image-id="' . $row['id'] . '"><i class="bi bi-arrow-counterclockwise"></i></button>';

    // Knop voor rechtsom draaien
    echo '<button class="btn btn-secondary btn-sm rotate-right" data-image-id="' . $row['id'] . '"><i class="bi bi-arrow-clockwise"></i></button>';

                
                ?>


                <button value="<?php echo $row['id']; ?>"
                        data-message="Weet je zeker dat je <?php echo $image; ?> wilt verwijderen?"
                        id="btn<?php echo $row['id']; ?>"
                        class="btn btn-danger btn-sm btn-ok"
                        data-bs-toggle="modal"
                        data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $row['id']; ?>"></i>
                </button>
                <?php
            }
            echo '</div>';
        }
    }
    echo '</div>'; // Sluit de row
}
echo '</div>'; // Sluit de container
?>
