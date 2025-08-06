<?php
/**
 * Language Selector Form Component voor Admin Content
 * 
 * Include dit bestand in je admin forms om een language selector toe te voegen
 * 
 * Vereiste variabelen:
 * - $menu (menulist class instance)  
 * - $group_id (site group ID)
 * - $current_lang_code (huidige taalcode, optioneel)
 */

// Default waarden
$current_lang_code = $current_lang_code ?? 'nl';
$field_name = $field_name ?? 'lang_code';
$show_translation_tools = $show_translation_tools ?? true;

// Haal beschikbare talen op
if (isset($menu) && isset($group_id)) {
    $site_languages = $menu->getSiteLanguages();
    $linked_languages = [];
    
    foreach ($site_languages as $lang) {
        $linked = $menu->getLinkedLanguages($group_id, $lang['locale']);
        if (!empty($linked)) {
            $linked_languages[] = $lang;
        }
    }
} else {
    $linked_languages = [['locale' => 'nl', 'label' => 'Nederlands']];
}
?>

<div class="language-management-section">
    <div class="form-group">
        <label for="<?php echo $field_name; ?>">
            <i class="bx bx-world"></i> Taal:
        </label>
        <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="form-control" required>
            <?php foreach ($linked_languages as $lang): ?>
                <option value="<?php echo htmlspecialchars($lang['locale']); ?>" 
                        <?php echo ($current_lang_code == $lang['locale']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($lang['label']); ?> 
                    (<?php echo strtoupper($lang['locale']); ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">
            Selecteer de taal voor dit content item
        </small>
    </div>

    <?php if ($show_translation_tools && count($linked_languages) > 1): ?>
    <div class="translation-tools">
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bx bx-transfer-alt"></i> Vertaal Opties
                </h6>
            </div>
            <div class="card-body">
                <?php if (isset($content_id) && !empty($content_id)): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Dupliceer voor andere talen:</label>
                            <div class="translation-checkboxes">
                                <?php foreach ($linked_languages as $lang): ?>
                                    <?php if ($lang['locale'] != $current_lang_code): ?>
                                        <div class="form-check">
                                            <input class="form-check-input duplicate-lang" 
                                                   type="checkbox" 
                                                   value="<?php echo $lang['locale']; ?>" 
                                                   id="duplicate_<?php echo $lang['locale']; ?>">
                                            <label class="form-check-label" for="duplicate_<?php echo $lang['locale']; ?>">
                                                <?php echo $lang['label']; ?>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="duplicate-content">
                                <i class="bx bx-copy"></i> Dupliceer Content
                            </button>
                        </div>
                        
                        <div class="col-md-6">
                            <label>Bestaande vertalingen:</label>
                            <div class="existing-translations">
                                <div id="translation-links">
                                    <!-- Wordt gevuld door JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle"></i>
                        Sla dit item eerst op om vertaal opties te zien.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.language-management-section .translation-tools {
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: 15px;
}

.translation-checkboxes {
    max-height: 120px;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
    background-color: #f9f9f9;
}

.translation-checkboxes .form-check {
    margin-bottom: 5px;
}

.existing-translations {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
    background-color: #f9f9f9;
    min-height: 100px;
}

.translation-link {
    display: block;
    padding: 5px 10px;
    margin: 2px 0;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 3px;
    text-decoration: none;
    color: #333;
}

.translation-link:hover {
    background-color: #e9ecef;
    text-decoration: none;
}

.translation-link .badge {
    float: right;
}
</style>

<script>
$(document).ready(function() {
    
    // Laad bestaande vertalingen
    function loadExistingTranslations() {
        <?php if (isset($content_id) && !empty($content_id)): ?>
        const contentId = <?php echo json_encode($content_id); ?>;
        const currentLocation = <?php echo json_encode($location ?? ''); ?>;
        const groupId = <?php echo json_encode($group_id ?? 1); ?>;
        
        $.ajax({
            url: '../functions/get_translations.php',
            method: 'POST',
            data: {
                content_id: contentId,
                location: currentLocation,
                group_id: groupId,
                current_lang: '<?php echo $current_lang_code; ?>'
            },
            success: function(response) {
                $('#translation-links').html(response);
            },
            error: function() {
                $('#translation-links').html('<small class="text-muted">Kon vertalingen niet laden</small>');
            }
        });
        <?php endif; ?>
    }
    
    // Dupliceer content voor geselecteerde talen
    $('#duplicate-content').click(function() {
        const selectedLangs = [];
        $('.duplicate-lang:checked').each(function() {
            selectedLangs.push($(this).val());
        });
        
        if (selectedLangs.length === 0) {
            alert('Selecteer minimaal één taal om naar te dupliceren.');
            return;
        }
        
        if (!confirm('Weet je zeker dat je dit content wilt dupliceren voor ' + selectedLangs.length + ' talen?')) {
            return;
        }
        
        const button = $(this);
        button.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Dupliceren...');
        
        $.ajax({
            url: '../functions/duplicate_content.php',
            method: 'POST',
            data: {
                content_id: <?php echo json_encode($content_id ?? 0); ?>,
                target_languages: selectedLangs,
                table: '<?php echo $table ?? 'group_content'; ?>'
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert('Content succesvol gedupliceerd voor ' + result.created + ' talen.');
                    $('.duplicate-lang:checked').prop('checked', false);
                    loadExistingTranslations();
                } else {
                    alert('Fout bij dupliceren: ' + result.message);
                }
            },
            error: function() {
                alert('Er is een fout opgetreden bij het dupliceren.');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="bx bx-copy"></i> Dupliceer Content');
            }
        });
    });
    
    // Laad vertalingen bij pagina load
    loadExistingTranslations();
    
    // Herlaad vertalingen als taal wijzigt
    $('#<?php echo $field_name; ?>').change(function() {
        loadExistingTranslations();
    });
});
</script>