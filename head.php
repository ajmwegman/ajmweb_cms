<?php
$meta_title = $meta_title ?? ($title ?? '');
$meta_description = $meta_description ?? '';
$meta_image = $meta_image ?? '';
?>
<title><?= htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="description" content="<?= htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:title" content="<?= htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:description" content="<?= htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
<?php if (!empty($meta_image)): ?>
<meta property="og:image" content="<?= htmlspecialchars($meta_image, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
