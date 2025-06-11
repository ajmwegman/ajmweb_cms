<?php
require_once($_SERVER['DOCUMENT_ROOT']."/functions/carousel.php");

$carousel = new carousel($pdo);

$items      = $carousel->getItems();
$settings   = $carousel->getCarouselSettings($group_id);

/* counters */
$counter    = count($items);
$buttons    = 0;
$slide      = 0;

#settings
$carouselId = 'carousel';
$carousel_indicators = $settings['indicators'];
$carousel_buttons = $settings['buttons'];
$carousel_location = $settings['folder'];
$height  = $settings['height'];
$speed = $settings['speed'];
?>
<div id="<?php echo $carouselId; ?>" class="carousel slide" data-bs-ride="<?php echo $carouselId; ?>" data-bs-interval="<?php echo $speed; ?>">
 
<?php if($carousel_indicators == 'y' && $counter > 1) { ?>
    <div class="carousel-indicators">
    <?php echo '';
    foreach($items as $button) {
        
        $slidenum = $buttons+1;
        echo '<button type="button" data-bs-target="#'.$carouselId.'" data-bs-slide-to="'.$buttons.'" class="';
        echo ($buttons == 0) ? 'active' : '';
        echo '" aria-current="true" aria-label="Slide '.$slidenum.'"></button>';

        $buttons++;
    }
    ?>
    </div>
<?php } ?>

<div class="carousel-inner">
    
    <?php
    foreach($items as $slices) {
        
        $image      = $slices['image'];
        $subject    = $slices['subject'];
        $category   = $slices['category'];
        $url        = $slices['url'];
        $description = $slices['description'];
        ?>
        <div class="carousel-item<?php echo ($slide === 0) ? ' active' : ''; ?>">
            <div style="max-height: <?php echo $height; ?>px;min-height: <?php echo $height; ?>px;">
            <img src="/<?php echo $carousel_location.'/'.$image; ?>" class="d-block w-100" alt="<?php echo $subject; ?>" >
            </div>
            <div class="absolute-div">
                <div class="carousel-caption">
                    <h2><?php echo $subject; ?></h2>
                    <h4><?php echo $category; ?></h4>
                    <?php if(!empty($url)) { ?>
                    <a href="<?php echo $url; ?>" class="btn btn-primary"><?php echo $description; ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
        $slide++;
    }
    ?>
    
  </div><!-- inner -->
    
    <?php if($carousel_buttons == 'y' && $counter > 1) { ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Vorige</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Volgende</span>
      </button>
    <?php } ?>
</div>