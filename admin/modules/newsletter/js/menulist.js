$(document).ready(function() {
    var location = '/admin/modules/newsletter/';
    
    // Newsletter form submission with AJAX
    $('#newsletterForm').ajaxForm({
        url: location + 'bin/campaign_actions.php',
        dataType: 'json',
        success: function(data) {
            if (data.success && data.newsletter_id) {
                // Close modal and reset form
                $('#newNewsletterModal').modal('hide');
                $('#newsletterForm')[0].reset();
                
                // Show success message
                $('#display').fadeIn("slow").html(data.message).delay(1000).fadeOut("slow");
                
                // Refresh the table to show the new newsletter
                $("#menulist").load(location + "bin/summary_load.php", function() {
                    // Then redirect to edit page for the new newsletter
                    setTimeout(function() {
                        window.location.href = '/admin/newsletter/edit/' + data.newsletter_id;
                    }, 1500);
                });
            } else {
                // Show error message
                $('#display').fadeIn("slow").html(data.message).delay(2000).fadeOut("slow");
            }
        },
        error: function() {
            $('#display').fadeIn("slow").html('<div class="alert alert-danger" role="alert">Er is een fout opgetreden</div>').delay(2000).fadeOut("slow");
        }
    });
    
    // Delete button functionality with modal confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name') || 'deze nieuwsbrief';
        
        // Set the ID for the modal
        $('#RowId').val(id);
        
        // Update modal message
        $('.dialogmessage').html('Weet je zeker dat je <strong>' + name + '</strong> wilt verwijderen?');
        
        // Show the modal
        $('#dialogModal').modal('show');
    });
    
    // Handle delete confirmation from modal
    $(document).on('click', '#dialogModal .btn-ok', function() {
        var id = $('#RowId').val();
        
        $.ajax({
            type: "post",
            data: { 
                action: 'delete',
                campaign_id: id,
                ajax: 'true'
            },
            url: location + "bin/campaign_actions.php", 
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $("#row" + id).fadeOut(function() {
                        $(this).remove();
                    });
                    $('#display').fadeIn("slow").html(data.message).delay(2000).fadeOut("slow");
                } else {
                    $('#display').fadeIn("slow").html(data.message).delay(2000).fadeOut("slow");
                }
                // Hide modal
                $('#dialogModal').modal('hide');
            },
            error: function() {
                $('#display').fadeIn("slow").html('<div class="alert alert-danger" role="alert">Er is een fout opgetreden bij het verwijderen van de campagne.</div>').delay(2000).fadeOut("slow");
                // Hide modal
                $('#dialogModal').modal('hide');
            }
        });
    });
    
    // Auto-resize textarea in modal
    $('#content').on('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    
    // Modal events
    $('#newNewsletterModal').on('hidden.bs.modal', function() {
        // Reset form when modal is closed
        $('#newsletterForm')[0].reset();
        $('#content').css('height', 'auto');
    });
    
    // Show loading indicator - removed to avoid extra messages
    
    // Send campaign button functionality
    $(document).on('click', '.send-campaign', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name') || 'deze nieuwsbrief';
        
        // Confirm before sending
        if (!confirm('Weet je zeker dat je "' + name + '" wilt verzenden? Dit kan niet ongedaan worden gemaakt.')) {
            return;
        }
        
        // Disable button and show loading
        var $button = $(this);
        var originalHtml = $button.html();
        $button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');
        
        $.ajax({
            type: "post",
            data: { 
                action: 'send',
                campaign_id: id,
                ajax: 'true'
            },
            url: location + "bin/campaign_actions.php", 
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Update button to show sent status
                    $button.removeClass('btn-outline-success send-campaign')
                          .addClass('btn-outline-success')
                          .prop('disabled', true)
                          .html('<i class="bi bi-check-circle"></i>')
                          .attr('title', 'Verzonden');
                    
                    // Update status badge
                    var $badge = $button.closest('.row').find('.badge');
                    $badge.removeClass().addClass('badge bg-success').text('Verzonden');
                    
                    $('#display').fadeIn("slow").html(data.message).delay(2000).fadeOut("slow");
                } else {
                    // Restore button
                    $button.prop('disabled', false).html(originalHtml);
                    $('#display').fadeIn("slow").html(data.message).delay(2000).fadeOut("slow");
                }
            },
            error: function() {
                // Restore button
                $button.prop('disabled', false).html(originalHtml);
                $('#display').fadeIn("slow").html('<div class="alert alert-danger" role="alert">Er is een fout opgetreden bij het verzenden van de nieuwsbrief.</div>').delay(2000).fadeOut("slow");
            }
        });
    });
}); 