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
    $action = isset( $_GET[ 'action' ] ) ? $_GET[ 'action' ] : "";
    $id = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : "";

    // Debug output
    echo "<!-- Debug: Module: {$module}, Action: {$action}, ID: {$id} -->";
    
    if(!empty($module)) { 
        $module_path = "modules/{$module}/index.php";
        echo "<!-- Debug: Loading module path: {$module_path} -->";
        
        if (file_exists($module_path)) {
            require_once( $module_path ); 
        } else {
            echo "<!-- Debug: Module file not found: {$module_path} -->";
            echo '<div class="alert alert-danger">Module niet gevonden: ' . htmlspecialchars($module) . '</div>';
        }
    } else { 
        require_once( "template/frontpage.php" ); 
    }
  ?>
</div>
<?php require_once("template/footer.php"); ?>