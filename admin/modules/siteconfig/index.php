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
    <ul class="nav nav-tabs" id="siteConfigTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="area-tab" data-bs-toggle="tab" data-bs-target="#area" type="button" role="tab" aria-controls="area" aria-selected="true">Werkgebieden</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="colors-tab" data-bs-toggle="tab" data-bs-target="#colors" type="button" role="tab" aria-controls="colors" aria-selected="false">Kleuren</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">SEO</button>
        </li>
    </ul>
    <div class="tab-content pt-3" id="siteConfigTabsContent">
        <div class="tab-pane fade show active" id="area" role="tabpanel" aria-labelledby="area-tab">
            <?php echo $area; ?>
        </div>
        <div class="tab-pane fade" id="colors" role="tabpanel" aria-labelledby="colors-tab">
            <?php require_once("forms/colors.php"); ?>
        </div>
        <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
            <?php require_once("forms/seo.php"); ?>
        </div>
    </div>
</div>

<script src="/admin/modules/siteconfig/js/js.js"></script>
