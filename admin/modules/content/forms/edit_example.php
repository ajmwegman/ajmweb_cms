<?php
/**
 * Voorbeeld van hoe een bestaand admin edit formulier kan worden aangepast
 * voor multi-language ondersteuning
 * 
 * Dit is een voorbeeld gebaseerd op de bestaande content module structuur
 */

// Bestaande code blijft hetzelfde...
// Alleen de taal selector en vertaal functies worden toegevoegd

// Haal content gegevens op (bestaande code)
$content_id = $_GET['id'] ?? 0;
if ($content_id) {
    $stmt = $pdo->prepare("SELECT * FROM group_content WHERE id = ?");
    $stmt->execute([$content_id]);
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$content) {
        echo "Content niet gevonden";
        exit;
    }
} else {
    // Nieuwe content
    $content = [
        'title' => '',
        'content' => '',
        'lang_code' => 'nl',
        'status' => 'y',
        'location' => $_GET['location'] ?? '',
        'group_id' => $group_id
    ];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <?php echo $content_id ? 'Bewerk Content' : 'Nieuwe Content'; ?>
                    </h4>
                </div>
                
                <div class="card-body">
                    <form id="content-form" method="post" action="bin/save.php">
                        <input type="hidden" name="id" value="<?php echo $content_id; ?>">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Bestaande velden -->
                                <div class="form-group">
                                    <label for="title">Titel:</label>
                                    <input type="text" 
                                           name="title" 
                                           id="title" 
                                           class="form-control autosave" 
                                           value="<?php echo htmlspecialchars($content['title']); ?>"
                                           data-set="<?php echo $content_id; ?>"
                                           data-field="title"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="location">Locatie:</label>
                                    <input type="text" 
                                           name="location" 
                                           id="location" 
                                           class="form-control autosave" 
                                           value="<?php echo htmlspecialchars($content['location']); ?>"
                                           data-set="<?php echo $content_id; ?>"
                                           data-field="location"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="content">Content:</label>
                                    <textarea name="content" 
                                              id="content" 
                                              class="form-control wysiwyg autosave" 
                                              rows="10"
                                              data-set="<?php echo $content_id; ?>"
                                              data-field="content"><?php echo htmlspecialchars($content['content']); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Status veld (bestaand) -->
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="draft" <?php echo $content['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                        <option value="published" <?php echo $content['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                    </select>
                                </div>
                                
                                <!-- NIEUWE TAAL SECTIE -->
                                <?php 
                                // Set variabelen voor language selector
                                $current_lang_code = $content['lang_code'];
                                $show_translation_tools = true;
                                $table = 'group_content';
                                $location = $content['location'];
                                
                                // Include de language selector
                                include 'forms/language_selector.php'; 
                                ?>
                                
                                <!-- Bestaande metadata velden kunnen hier blijven -->
                                <div class="form-group">
                                    <label>SEO URL:</label>
                                    <input type="text" 
                                           name="seo_url" 
                                           class="form-control" 
                                           value="<?php echo htmlspecialchars($content['seo_url'] ?? ''); ?>">
                                    <small class="form-text text-muted">Automatisch gegenereerd als leeg gelaten</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>Meta Description:</label>
                                    <textarea name="meta_description" 
                                              class="form-control" 
                                              rows="3"><?php echo htmlspecialchars($content['meta_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Opslaan
                                </button>
                                
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Terug
                                </a>
                                
                                <?php if ($content_id): ?>
                                <a href="?action=delete&id=<?php echo $content_id; ?>" 
                                   class="btn btn-danger float-right"
                                   onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?')">
                                    <i class="bx bx-trash"></i> Verwijderen
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Bestaande JavaScript kan blijven...
    
    // Nieuwe functionaliteit voor taal management
    $('#lang_code').change(function() {
        const newLang = $(this).val();
        const currentTitle = $('#title').val();
        
        // Waarschuw gebruiker als er unsaved changes zijn
        if (currentTitle && !currentTitle.startsWith('[TRANSLATE]')) {
            if (!confirm('Je hebt wijzigingen die mogelijk verloren gaan. Doorgaan?')) {
                return false;
            }
        }
        
        // Update form action om nieuwe taal te reflecteren
        const form = $('#content-form');
        const action = form.attr('action');
        
        // Je zou hier kunnen redirecten naar een nieuwe URL met de taal parameter
        // of de form dynamisch kunnen updaten
    });
    
    // Auto-save functionaliteit (bestaand, blijft hetzelfde)
    $('.autosave').on('keyup change', function() {
        // Bestaande autosave code...
    });
});
</script>

<style>
/* Extra styling voor de nieuwe language management sectie */
.language-management-section {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.language-management-section h6 {
    color: #495057;
    margin-bottom: 15px;
}

/* Responsive aanpassingen */
@media (max-width: 768px) {
    .translation-tools .row > div {
        margin-bottom: 15px;
    }
}
</style>