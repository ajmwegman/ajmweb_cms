<?php
require_once("src/site_config.php");

$site_config = new siteConfig($pdo);
$menu = new menu($pdo);	

$config = $menu->getSiteConfig( $sid );
$area = $site_config->area_list($sid);

if (isset($config[0])) {
    $data = $config[0];
} else {
    echo "Geen data beschikbaar.";
    exit; // Of andere logica die je wilt uitvoeren als er geen data is
}

echo "<h2>" . $menu->getSiteName( $sid ) . "</h2>";
echo "<small>Site ID: " . $sid . "</small>";
?>

<div class="container mt-5">
    <div class="accordion" id="myAccordion">

        <!-- Eerste item: Werkgebieden -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <strong>Werkgebieden</strong>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#myAccordion">
                <div class="accordion-body">
                    <?php echo $area; ?>
                </div>
            </div>
        </div>

        <!-- Tweede item: Kleuren -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <strong>Kleuren</strong>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#myAccordion">
                <div class="accordion-body">
                    <?php require_once("forms/colors.php"); ?>
                </div>
            </div>
        </div>

        <!-- Derde item: SEO -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <strong>SEO</strong>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#myAccordion">
                <div class="accordion-body">
                    <?php require_once("forms/seo.php"); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/admin/modules/siteconfig/js/js.js"></script>