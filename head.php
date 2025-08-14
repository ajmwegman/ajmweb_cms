<!-- Additional SEO and GEO meta tags -->
<meta name="robots" content="index,follow">
<link rel="canonical" href="<?php echo htmlspecialchars($loc_website . $_SERVER['REQUEST_URI']); ?>">

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo htmlspecialchars(($title ?? '') . ' | ' . $info['web_naam']); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($info['description']); ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo htmlspecialchars($loc_website . $_SERVER['REQUEST_URI']); ?>">
<?php if (!empty($info['og_image'])): ?>
<meta property="og:image" content="<?php echo htmlspecialchars($loc_website . '/' . ltrim($info['og_image'], '/')); ?>">
<?php endif; ?>

<!-- GEO Tags -->
<?php if (!empty($info['geo_region'])): ?>
<meta name="geo.region" content="<?php echo htmlspecialchars($info['geo_region']); ?>">
<?php endif; ?>
<?php if (!empty($info['geo_placename'])): ?>
<meta name="geo.placename" content="<?php echo htmlspecialchars($info['geo_placename']); ?>">
<?php endif; ?>
<?php if (!empty($info['geo_position'])): ?>
<meta name="geo.position" content="<?php echo htmlspecialchars($info['geo_position']); ?>">
<meta name="ICBM" content="<?php echo htmlspecialchars(str_replace(';', ',', $info['geo_position'])); ?>">
<?php endif; ?>
