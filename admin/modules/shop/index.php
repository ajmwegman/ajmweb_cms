<?php
// Debug informatie
if (isset($module)) {
    echo "<!-- Debug: Module = {$module}, Action = {$action}, ID = {$id} -->";
}
?>

<h2>Winkel beheer</h2>

<?php require_once("forms/category.php"); ?>
<?php require_once("forms/product.php"); ?>

<script src="/admin/modules/shop/js/product_form.js" type="text/javascript"></script>
