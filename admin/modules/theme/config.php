<?php
require_once("src/theme.class.php");

$themeConfig = new themeConfig($pdo);


// Retrieve latest image
//$thumbnailUrl = $themeConfig->getLatestImage();
//$resonse = json_encode(array('thumbnailUrl' => $thumbnailUrl));    
?>
<h2>Logo beheer</h2>
<?php require_once("forms/add.php"); ?>
