<?php
require_once($theme."/functions/gallery.php");

$gallery = new gallery($pdo);

$items      = $gallery->getItems();
$categories = $gallery->getCategories();

$numCategories = count($categories);

#settings
$collumns = 4;
$location = '/gallery/';

$numCollumns = 12/$collumns;
?>
<!-- ======= Gallery Section ======= -->
    <section class="container">

    <div class="gallery">

        <?php if($numCategories > 1) { ?>
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="gallery-buttons">
                <?php 
                foreach($categories as $value) {
    
                    $cat_name = $value['category'];
                    $filter_name = strtolower(str_replace(" ", "-", $cat_name));

                    echo "<li data-filter=\".{$filter_name}\">{$cat_name}</li>";
                } 
                ?>            
              <li data-filter="*" class="filter-active">Toon alles</li>
            </ul>
          </div>
        </div>
        <?php } ?>

        <div class="row gallery-container">
        <?php foreach($items as $item) {    
            $category   = $item['category'];
            $image      = $item['image'];
            $subject    = $item['subject'];
            $filtername = strtolower(str_replace(" ", "-", $category));
        ?>
          <div class="col-lg-<?php echo $numCollumns; ?> col-md-6 gallery-item <?php echo $filtername; ?>">
            <img src="<?php echo $location.$image; ?>" class="img-fluid" alt="<?php echo $subject; ?>">
            <div class="gallery-info">
              <h4><?php echo $subject; ?></h4>
              <p><?php echo $category; ?></p>
              <a href="<?php echo $location.$image; ?>" data-gallery="imageGallery" class="gallery-lightbox preview-link" title="<?php echo $subject; ?>"><i class="bx bx-plus"></i></a>
             <!-- <a href="gallery-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a> -->
            </div>
          </div>
         <?php } ?>


        </div>

    </div><!-- End Portfolio Section -->
</section>