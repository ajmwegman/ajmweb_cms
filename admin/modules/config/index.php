<?php
$section_title = "Configuratie";
$config = $menu->getConfig( $group_id );

$accordionItems = array("Website instellingen", "Bedrijfsgevens", "Taal");
$accordionForms = array("site.php", "company.php", "language.php");

$http_locations = array( "http://www.", "http://", "https://", "https://www." );

echo "<h2>" . $menu->getGroupName( $group_id ) . "</h2>";
echo "<small>Groep ID: " . $group_id . "</small>";

require_once("../admin/template/navtabs.php");
?>
<h2><?php echo $section_title; ?></h2>
<div class="row mt-4">
  <?php
  foreach ( $config as $row => $data ) {
    ?>
    
  <div class="accordion" id="accordionExample">
<?php

foreach ($accordionItems as $key => $item) {
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?php echo $key; ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse<?php echo $key; ?>" aria-expanded="false"
                    aria-controls="collapse<?php echo $key; ?>">
                <strong><?php echo $item; ?></strong>
            </button>
        </h2>
        <div id="collapse<?php echo $key; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $key; ?>"
             data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <?php include "forms/".$accordionForms[$key]; ?>
            </div>
        </div>
    </div>
    <?php
}
?>
</div>
  <? } ?>
</div>
<script src="/admin/modules/config/js/js.js"></script>