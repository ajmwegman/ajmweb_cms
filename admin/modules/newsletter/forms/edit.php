<?php
// Newsletter Campaign Edit Form
// Database connection already available from main CMS
?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5>Nieuwsbrief Bewerken</h5>
            </div>
            <div class="card-body">
                <form id="campaignEditForm" method="post" action="bin/campaign_actions.php">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="campaign_id" value="<?php echo $id; ?>">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="subject" class="form-label">Onderwerp *</label>
                            <input type="text" class="form-control autosave" id="subject" name="subject" 
                                   value="<?php echo htmlspecialchars($subject); ?>" 
                                   data-set="<?php echo $id; ?>" data-field="subject" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="target_audience" class="form-label">Doelgroep</label>
                            <select class="form-select autosave" id="target_audience" name="target_audience" data-set="<?php echo $id; ?>" data-field="target_audience">
                                <option value="all" <?php echo ($target_audience == 'all') ? 'selected' : ''; ?>>Alle abonnees</option>
                                <option value="active" <?php echo ($target_audience == 'active') ? 'selected' : ''; ?>>Alleen actieve abonnees</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sender_name" class="form-label">Afzender Naam</label>
                            <input type="text" class="form-control autosave" id="sender_name" name="sender_name" 
                                   value="<?php echo htmlspecialchars($sender_name); ?>"
                                   data-set="<?php echo $id; ?>" data-field="sender_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sender_email" class="form-label">Afzender E-mail</label>
                            <input type="email" class="form-control autosave" id="sender_email" name="sender_email" 
                                   value="<?php echo htmlspecialchars($sender_email); ?>"
                                   data-set="<?php echo $id; ?>" data-field="sender_email">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scheduled_at" class="form-label">Gepland voor</label>
                            <input type="datetime-local" class="form-control autosave" id="scheduled_at" name="scheduled_at" 
                                   value="<?php echo $scheduled_at ? date('Y-m-d\TH:i', strtotime($scheduled_at)) : ''; ?>"
                                   data-set="<?php echo $id; ?>" data-field="scheduled_at">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select autosave" id="status" name="status" data-set="<?php echo $id; ?>" data-field="status">
                                <option value="draft" <?php echo ($status == 'draft') ? 'selected' : ''; ?>>Concept</option>
                                <option value="ready" <?php echo ($status == 'ready') ? 'selected' : ''; ?>>Klaar voor verzending</option>
                                <option value="scheduled" <?php echo ($status == 'scheduled') ? 'selected' : ''; ?>>Gepland</option>
                                <option value="sending" <?php echo ($status == 'sending') ? 'selected' : ''; ?>>Verzenden</option>
                                <option value="sent" <?php echo ($status == 'sent') ? 'selected' : ''; ?>>Verzonden</option>
                                <option value="cancelled" <?php echo ($status == 'cancelled') ? 'selected' : ''; ?>>Geannuleerd</option>
                                <option value="archived" <?php echo ($status == 'archived') ? 'selected' : ''; ?>>Gearchiveerd</option>
                            </select>
                        </div>
                    </div>
                    

                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Inhoud *</label>
                        <textarea class="form-control summernote" id="content" name="content" rows="15" 
                                  data-set="<?php echo $id; ?>" data-field="content" required><?php echo htmlspecialchars($content); ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Loading indicator for auto-save feedback -->
    <div id="display" class="alert-fixed" style="display: none;"></div>

    <style>
    @keyframes spinner-grow {
        0% { transform: scale(0); }
        50% { opacity: 1; transform: none; }
    }
    </style>

</div>

<div class="row mt-4">
    <div class="col-md-4">
        <button type="button" class="btn btn-success" id="readyForSendBtn">
            <i class="bi bi-send"></i> Klaar voor verzenden
        </button>
    </div>
    <div class="col-md-4">
        <button type="button" class="btn btn-secondary" id="saveAsDraftBtn">
            <i class="bi bi-save"></i> Opslaan als concept
        </button>
    </div>
    <div class="col-md-4">
        <button type="button" class="btn btn-info" id="previewBtn">
            <i class="bi bi-eye"></i> Voorvertoning
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    var location = '/admin/modules/newsletter/';
    
    // Auto-save functionality
    var timer;
    $('.autosave').on('keyup change input', function() {
        clearTimeout(timer);
        
        var id = $(this).attr("data-set");
        var field = $(this).attr("data-field");
        var value = $(this).val();
        
        timer = setTimeout(function() {
            $.ajax({
                type: "post",
                data: {
                    id: id,
                    field: field,
                    value: value,
                },
                url: location + "bin/autosave.php?cache=" + Math.random(),
                success: function(result){
                    $('#loading').show().html(result).delay(300).fadeOut("fast");
                },
                error: function(xhr, status, error) {
                    $('#loading').show().html('<div class="alert alert-danger" role="alert" style="margin: 0;">Er is iets fout gegaan!</div>').delay(300).fadeOut("fast");
                }
            });
        }, 400);
    });
    
    // Initialize Summernote editor
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    
    // Summernote auto-save
    $('#content').on('summernote.change', function(we, contents, $editable) {
        clearTimeout(timer);
        
        var id = $(this).attr("data-set");
        var field = $(this).attr("data-field");
        var value = contents;
        
        timer = setTimeout(function() {
            $.ajax({
                type: "post",
                data: {
                    id: id,
                    field: field,
                    value: value,
                },
                url: location + "bin/autosave.php?cache=" + Math.random(),
                success: function(result){
                    $('#loading').show().html(result).delay(300).fadeOut("fast");
                }
            });
        }, 400);
    });
    
    // Ready for Send button
    $('#readyForSendBtn').click(function() {
        var content = $('#content').summernote('code');
        var subject = $('#subject').val();
        
        if (!subject) {
            $('#display').fadeIn("fast").html('<div class="alert alert-warning" role="alert">Vul eerst een onderwerp in.</div>').delay(2000).fadeOut("fast");
            return;
        }
        
        if (!content || content.trim() === '') {
            $('#display').fadeIn("fast").html('<div class="alert alert-warning" role="alert">Vul eerst inhoud in.</div>').delay(2000).fadeOut("fast");
            return;
        }
        
        // Update status to ready and redirect to overview
        $.ajax({
            type: "post",
            data: {
                id: <?php echo $id; ?>,
                field: 'status',
                value: 'ready'
            },
            url: location + "bin/autosave.php?cache=" + Math.random(),
            success: function(result) {
                $('#display').fadeIn("fast").html('<div class="alert alert-success" role="alert">Nieuwsbrief is klaar voor verzending! Doorsturen naar overzicht...</div>').delay(2000).fadeOut("fast");
                $('#status').val('ready');
                
                // Redirect to overview page after 2 seconds
                setTimeout(function() {
                    window.location.href = '/admin/newsletter/';
                }, 2000);
            },
            error: function(xhr, status, error) {
                $('#display').fadeIn("fast").html('<div class="alert alert-danger" role="alert">Fout bij updaten status!</div>').delay(2000).fadeOut("fast");
            }
        });
    });
    
    // Save as Draft button
    $('#saveAsDraftBtn').click(function() {
        var content = $('#content').summernote('code');
        var subject = $('#subject').val();
        
        if (!subject) {
            $('#display').fadeIn("fast").html('<div class="alert alert-warning" role="alert">Vul eerst een onderwerp in.</div>').delay(2000).fadeOut("fast");
            return;
        }
        
        // Update status to draft and redirect to homepage
        $.ajax({
            type: "post",
            data: {
                id: <?php echo $id; ?>,
                field: 'status',
                value: 'draft'
            },
            url: location + "bin/autosave.php?cache=" + Math.random(),
            success: function(result) {
                $('#display').fadeIn("fast").html('<div class="alert alert-success" role="alert">Opgeslagen als concept! Doorsturen naar hoofdpagina...</div>').delay(2000).fadeOut("fast");
                $('#status').val('draft');
                
                // Redirect to overview page after 2 seconds
                setTimeout(function() {
                    window.location.href = '/admin/newsletter/';
                }, 2000);
            }
        });
    });
    
    // Preview button
    $('#previewBtn').click(function() {
        var content = $('#content').summernote('code');
        var subject = $('#subject').val();
        
        if (!subject) {
            $('#display').fadeIn("fast").html('<div class="alert alert-warning" role="alert">Vul eerst een onderwerp in.</div>').delay(2000).fadeOut("fast");
            return;
        }
        
        // Open preview in new window
        var previewWindow = window.open('', '_blank');
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Voorvertoning: ${subject}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .preview-header { background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
                    .preview-content { max-width: 600px; margin: 0 auto; }
                </style>
            </head>
            <body>
                <div class="preview-header">
                    <h3>Voorvertoning</h3>
                    <p><strong>Onderwerp:</strong> ${subject}</p>
                </div>
                <div class="preview-content">
                    ${content}
                </div>
            </body>
            </html>
        `);
        previewWindow.document.close();
    });
    

});
</script> 