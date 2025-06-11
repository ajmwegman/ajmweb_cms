<?php
require_once __DIR__ . '/controller.php';

$menuGroups = Nav::getGroupsMenu($menu);
$menuSites = Nav::getSitesMenu($menu);
$menuModules = [
  [ 'href' => '/admin/banners/', 'label' => 'Banners' ],
  [ 'href' => '/admin/carousel/', 'label' => 'Carousel' ],
  [ 'href' => '/admin/gallery/', 'label' => 'Gallery' ],
  [ 'href' => '/admin/photoslider/', 'label' => 'Foto slider' ],
  [ 'href' => '/admin/reviews/', 'label' => 'Reviews' ],
  [ 'href' => '/admin/products/', 'label' => 'Producten' ],
  [ 'href' => '/admin/auctions/', 'label' => 'Veilingen' ]
];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-colorful shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="/admin/index.php">
      <img src="/admin/assets/images/logo_w.png" class="img-fluid" alt="Logo"/>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?= Nav::renderNavItem('/admin/index.php', 'Home', 'house') ?>
        <?= Nav::renderDropdown('webpagesNavbarDropdown', 'Mijn webpagina\'s', $menuGroups) ?>
        <?= Nav::renderDropdown('settingsNavbarDropdown', 'Instellingen', $menuSites) ?>
        <?= Nav::renderDropdown('modulesNavbarDropdown', 'Modules', $menuModules) ?>
        <li class="dropdown-header">Dynamische Modules:</li>
        <li><?= $menu->generateDynamicMenu('admin/modules', 'menu_cache.html', 3600); ?></li>
      </ul>

      <span class="navbar-text">
        <?= htmlspecialchars(Nav::getDaytime($login)) ?> <?= htmlspecialchars($firstname) ?>
      </span>

      <ul class="navbar-nav mb-lg-0 me-5">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userNavbarDropdown" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            <h3><i class="bi bi-person-circle"></i></h3>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="userNavbarDropdown">
            <li><a class="dropdown-item" href="/admin/admin_users/">Gebruikers</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/admin/logout.php">Afmelden</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div id="loading"></div>
