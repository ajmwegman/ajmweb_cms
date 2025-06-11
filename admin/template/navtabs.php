<?php
$module = isset($_GET["module"]) ? $_GET['module'] : 'menu';
$action = isset($_GET["action"]) ? $_GET['action'] : 'view';
$id 	= isset($_GET["id"]) ? $_GET['id'] : 0;

?>

<div class=" row mt-4 mb-4">
  
   <ul class="nav nav-tabs" id="myTab" role="tablist">
	   
    <li class="nav-item" role="presentation">
      <a class="nav-link <?php echo ($module == 'menu') ? "active" : ''; ?>" id="home-tab" href="/admin/menu/" role="tab" aria-controls="home" aria-selected="true">Menu</a>
    </li>
	   
    <li class="nav-item" role="presentation">
      <a class="nav-link <?php echo ($module == 'content') ? "active" : ''; ?>" id="profile-tab" href="/admin/content/" role="tab" aria-controls="content" aria-selected="false">Content</a>
    </li>
	   
    <li class="nav-item" role="presentation">
      <a class="nav-link <?php echo ($module == 'keywords') ? "active" : ''; ?>" id="settings-tab" href="/admin/keywords/" role="tab" aria-controls="keywords" aria-selected="false">Zoekwoorden</a>
    </li>
	   
	<li class="nav-item" role="presentation">
      <a class="nav-link <?php echo ($module == 'config') ? "active" : ''; ?>" id="settings-tab" href="/admin/config/" role="tab" aria-controls="config" aria-selected="false">Configuratie</a>
    </li>
	   
  </ul>

</div>

<?php 

//print_r($_GET);
if ($module == 'menu') { require_once("modules/menu/index.php"); } 
//if ($module == 'content' && $action != 'edit') { require_once("modules/content/index.php"); } 
if ($module == 'content' && $action == 'edit') { require_once("modules/content/edit.php"); } 
if ($module == 'keywords') { require_once("modules/keywords/index.php"); } 
if ($module == 'config') { require_once("modules/config/index.php"); } 
?>



