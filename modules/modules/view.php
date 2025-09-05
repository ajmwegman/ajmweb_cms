<!DOCTYPE html>
<html lang="nl"><head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $title; ?></title>
  <meta content="<?php echo $meta_description; ?>" name="description">
  <meta content="<?php echo $meta_keywords; ?>" name="keywords">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?><?php echo $theme; ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?php echo $site_location; ?>assets/css/transitions-optimized.css" rel="stylesheet">
</head>
<body>
<div id="display" class="alert-fixed"></div>
<?php require_once($theme."/sections/header.php"); ?>
<section class="mt-5">
  <div class="container mt-5">
    <h3>Modules</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($availableModules as $mod): ?>
      <div class="col">
        <div class="card h-100 text-center">
          <div class="card-body">
            <h5 class="card-title"><?php echo ucfirst($mod); ?></h5>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php for ($i = 0; $i < 5; $i++): ?>
      <div class="col">
        <div class="card h-100 text-center placeholder-card">
          <div class="card-body">
            <h5 class="card-title">Placeholder</h5>
          </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php require_once($theme."/sections/footer.php"); ?>
<script src="<?php echo $site_location; ?><?php echo $theme; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $site_location; ?><?php echo $theme; ?>/assets/js/main.js"></script>
<script src="<?php echo $site_location; ?>assets/js/transitions-optimizer.js"></script>
</body>
</html>
