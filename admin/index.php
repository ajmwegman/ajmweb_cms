<?php
require_once( "template/config.php" );
require_once( "bin/login_check.php" );
require_once( "template/head.php" );
?>
<body>
<?php
require_once( "template/navbar.php" );
?>
<input type="hidden" value="<?php echo $group_id; ?>" name="group_id" id="group_id">
<input type="hidden" value="<?php echo $sid; ?>" name="sid" id="sid">
<div id="display" class="alert-fixed"></div>
<div class="container mt-4">
  <?php
    $module = isset( $_GET[ 'module' ] ) ? $_GET[ 'module' ] : "";

    if(!empty($module)) { require_once( "modules/{$module}/index.php" ); } else { require_once( "template/frontpage.php" ); }
  ?>
</div>
<?php require_once("template/footer.php"); ?>