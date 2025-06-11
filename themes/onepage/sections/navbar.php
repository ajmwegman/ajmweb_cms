<?php $loc_website = "https://www.webstoremanager.nl"; ?>

<nav id="navbar" class="navbar">
  <ul>

<?php
$i = 0;
$nav = '';
foreach($menu as $row => $value) {
	
	$class = ($i == 0) ? 'nav-link scrollto active' : 'nav-link scrollto';

	$nav .= '<li><a class="'.$class.'" href="'.$loc_website."/".$value['location'].'">'.$value['title'].'</a></li>';
	$nav .= "\n";
	$i++;
}

echo $nav;
?>
  </ul>
  <i class="bi bi-list mobile-nav-toggle"></i> 
</nav>
<!-- .navbar -->