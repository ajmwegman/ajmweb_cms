<?php
$groups = $menu->getGroups();
$sites = $menu->getSiteId();
$daytime = $login->daytime(date("H"));
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-colorful shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="/admin/index.php"><img src="/admin/assets/images/logo_w.png" class="img-fluid" alt=""/></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/admin/index.php">Home</a>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mijn webpagina's
          </a>
          
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          	<?php foreach ( $groups as $key ) { 
			$id = $menu->getGroupId($key);  
			?>
      			<li><a class="dropdown-item" href="/admin/selector.php?id=<?php echo $id; ?>"><?php echo $key; ?></a> </li>
      		<?php } ?>
     
            </li>
          </ul>
        <li class="nav-item dropdown">
		    <a class="nav-link dropdown-toggle" href="#" id="sitesNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Instellingen
          </a>
            <ul class="dropdown-menu" aria-labelledby="sitesNavbarDropdown">

            <?php foreach ( $sites as $row => $key ) { 
			 $site = $key['id'];  
			 $webnaam = $key['web_naam'];
			?>
      			<li><a class="dropdown-item" href="/admin/site_select.php?sid=<?php echo $site; ?>"><?php echo $webnaam; ?></a> </li>
      		<?php } ?>
            </ul>
        </li>
        <li class="nav-item dropdown">
		    <a class="nav-link dropdown-toggle" href="#" id="sitesNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Modules
          </a>
            <ul class="dropdown-menu" aria-labelledby="sitesNavbarDropdown">
                <li><a class="dropdown-item" aria-current="banners" href="/admin/banners/">Banners</a></li>
                <li><a class="dropdown-item" aria-current="carousel" href="/admin/carousel/">Carousel</a></li>
                <li><a class="dropdown-item" aria-current="gallery" href="/admin/gallery/">Gallery</a><li>
                <li><a class="dropdown-item" aria-current="photoslider" href="/admin/photoslider/">Foto Slider</a><li>
                <li><a class="dropdown-item" aria-current="reviews" href="/admin/reviews/">Reviews</a></li>
                <li><a class="dropdown-item" aria-current="products" href="/admin/products/">Producten</a></li>
                <li><a class="dropdown-item" aria-current="auction" href="/admin/auctions/">Veilingen</a></li>
                <li><a class="dropdown-item" aria-current="shop" href="/admin/shop/">Winkel</a></li>
                <li><a class="dropdown-item" aria-current="newsletter_memberlist" href="/admin/newsletter_memberlist/">Nieuwsbrief leden</a></li>
                <li><a class="dropdown-item" aria-current="newsletter" href="/admin/newsletter/">Nieuwsbrief</a></li>
                <li><a class="dropdown-item" aria-current="customers" href="/admin/customers/">Klanten</a></li>
                <li><a class="dropdown-item" aria-current="orders" href="/admin/orders/">Bestellingen</a></li>
            </ul>
        </li>

        </li>
		  <!--
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
-->
      </ul>
    <span class="navbar-text"><?php echo $daytime; ?> <?php echo $firstname; ?></span>
      	<ul class="navbar-nav mr-auto mb-lg-0 me-5">
            
  <li class="nav-link dropdown-togggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
      <h3><i class="bi bi-person-circle"></i></h3>
</li>
  <ul class="dropdown-menu dropdown-menu-lg-end me-5">
    <li><a class="dropdown-item" href="/admin/admin_users/">Gebruikers</a></li>
    <!--<li><a class="dropdown-item" href="#">Menu item</a></li>-->
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="/admin/logout.php">Afmelden</a></li>
  </ul>
    </ul>
    </div>
  </div>
</nav>
<div id="loading"></div>