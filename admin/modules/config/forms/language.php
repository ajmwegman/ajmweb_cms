<?php $numcols = 6; ?>
<p>Selecteer een taal</p>

<div class="row">
<?php
    
    //print_r($_SESSION);

$languages = $menu->getSiteLanguages();

foreach ( $languages as $language ) {

  $lang_id = $language[ 'lang_id' ];
  $label = $language[ 'label' ];
  $locale = $language[ 'locale' ];
  $cols = 12 / $numcols;

$content = $menu->getLinkedLanguages($group_id, $locale);
    ?>
<div class="col-md-<?php echo $cols; ?>">
  <div class="form-check form-switch">
      <?php echo checkbox("languages[]", $locale, $label, $content, $extra='class="form-check-input save_lang"'); ?>
  </div>
</div>
<?php
}
?>
</div>


