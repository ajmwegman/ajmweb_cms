<?php
$privacy = $site->getSingleContent("privacy-statement");
?>

<div class="modal fade" id="privacy" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="privacyLabel" aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyLabel"><?php echo $privacy['title']; ?> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php //echo build_text($privacy['content'], $keywords, $replacers); 
          echo $privacy['content'];
          ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
      </div>
    </div>
  </div>
</div>