<!-- Eerste Navbar --b>
<header id="first-header" class="fixed-top">
  <nav class="navbar navbar-expand navbar-light bg-light" style="height: 40px;">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item custom-nav-item">
        <a class="nav-link" href="#"><i class="bi bi-person-fill"></i> Mijn Account</a>
      </li>
      <li class="nav-item custom-nav-item">
        <a class="nav-link" href="#"><i class="bi bi-headphones"></i> Support</a>
      </li>
      <li class="nav-item custom-nav-item">
        <a class="nav-link" href="#"><i class="bi bi-envelope-fill"></i> Contact</a>
      </li>
    </ul>
  </nav>
</header>

<!-- Tweede Navbar -->
<header id="header" class="fixed-top">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand logo" href="<?php echo $site_location; ?>index.php"><img class="img-fluid" src="../../../images/design23/logo.png" alt="<?php echo $title; ?>"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar2" aria-controls="navbar2" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbar2">
        <ul class="navbar-nav ms-auto">
          <?php
          $i = 0;
          $nav = '';
          foreach($menu as $row => $value) {
            $class = ($i == 0) ? 'nav-link scrollto active' : 'nav-link scrollto';
            $nav .= '<li class="nav-item"><a class="'.$class.'" href="'.$value['location'].'">'.$value['title'].'</a></li>';
            $nav .= "\n";
            $i++;
          }
          echo $nav;
          ?>
        </ul>
      </div>
    </nav>
  </div>
</header>

