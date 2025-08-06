<?php
// Newsletter Campaign Add Form
?>
<div class="card shadow">
    <div class="card-header">
        <h5>Nieuwe Campagne Aanmaken</h5>
    </div>
    <div class="card-body">
        <form method="post" action="bin/campaign_actions.php">
            <input type="hidden" name="action" value="add">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Onderwerp *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Inhoud *</label>
                        <textarea class="form-control" id="content" name="content" rows="15" required></textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="language" class="form-label">Taal *</label>
                        <select class="form-select" id="language" name="language" required>
                            <option value="">Selecteer taal</option>
                            <option value="nl">Nederlands</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft">Concept</option>
                            <option value="scheduled">Gepland</option>
                            <option value="sending">Verzenden</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">Gepland voor</label>
                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Campagne Opslaan
                        </button>
                        <a href="/admin/newsletter/" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Annuleren
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Simple WYSIWYG editor initialization
document.addEventListener('DOMContentLoaded', function() {
    // You can add a rich text editor here if needed
    // For now, we'll use a simple textarea
    const contentArea = document.getElementById('content');
    if (contentArea) {
        contentArea.addEventListener('input', function() {
            // Auto-resize textarea
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
});
</script> 