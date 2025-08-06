$(document).ready(function() {
    var location = '/admin/modules/newsletter_memberlist/';

    // make a new hash for menu item
    var hash = generate_token(12);
    $("#hash").val(hash);

    // prepare all forms for ajax submission
    var options = {
        target: '#display',
        success: function() {
            $("#menulist").load(location + "bin/summary_load.php");
            $('#display').fadeIn("slow").delay(2000).fadeOut("slow");
        }
    };

    $('#menuform').ajaxForm(options);

    // Search functionality
    var searchTimer;
    
    // Real-time search on keyup
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        var searchTerm = $(this).val();
        
        searchTimer = setTimeout(function() {
            performSearch(searchTerm);
        }, 300); // 300ms delay to avoid too many requests
    });

    // Search button click
    $('#searchButton').on('click', function() {
        var searchTerm = $('#searchInput').val();
        performSearch(searchTerm);
    });

    // Function to perform search
    function performSearch(searchTerm) {
        $.ajax({
            type: 'POST',
            url: location + 'bin/search.php',
            data: { search: searchTerm },
            success: function(response) {
                $('#menulist').html(response);
                initializeEventHandlers();
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
            }
        });
    }

    // Function to initialize event handlers for dynamically loaded content
    function initializeEventHandlers() {
        // Reinitialize switchbox handlers
        $(".switchbox").off('click').on('click', function() {
            var id = $(this).attr("data-set");
            var status = $(this).is(":checked") ? 'y' : 'n';

            $.ajax({
                type: "post",
                data: { id, status },
                url: location + "bin/activate.php",
                success: function(result) {
                    $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
                }
            });
        });

        // Reinitialize edit button handlers
        $(document).off('click', '.btn-edit').on('click', '.btn-edit', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-firstname').val($(this).data('firstname'));
            $('#edit-lastname').val($(this).data('lastname'));
            $('#edit-email').val($(this).data('email'));
        });

        // Reinitialize delete button handlers
        $(document).off('click', '.btn-delete').on('click', '.btn-delete', function() {
            var id = $(this).data('row-id');
            var row = $('.row[data-row-index="' + id + '"]');

            $.ajax({
                type: "post",
                data: { id: id },
                url: location + "bin/delete.php",
                success: function(result) {
                    row.remove();
                    $('#dialogModal').modal('hide');
                }
            });
        });
    }

    // switchbox click handler
    $(".switchbox").on('click', function() {
        var id = $(this).attr("data-set");
        var status = $(this).is(":checked") ? 'y' : 'n';

        $.ajax({
            type: "post",
            data: { id, status },
            url: location + "bin/activate.php",
            success: function(result) {
                $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
            }
        });
    });

    // modal show event
    $('#dialogModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var message = button.data('message'); // Extract message from data-* attributes
        var id = button.data('row-id'); // Extract id from data-* attributes
        var modal = $(this);

        // Update the modal's content.
        modal.find('.modal-title').text('Let op!');
        modal.find('.modal-body .dialogmessage').text(message);

        // Attach the row id to the delete button
        modal.find('.btn-delete').data('row-id', id);
    });

    // delete button click handler
    $(".btn-delete").on('click', function() {
        var id = $(this).data('row-id'); // Get the row id
        var row = $('.row[data-row-index="' + id + '"]'); // Find the row with the right id

        // Perform your delete logic here
        // For example, make an AJAX call to delete the entry from the database
        $.ajax({
            type: "post",
            data: { id: id },
            url: location + "bin/delete.php",
            success: function(result) {
                row.remove(); // Remove the row from the DOM
                $('#dialogModal').modal('hide'); // Hide the modal
            }
        });
    });

    // Edit button click handler
    $(document).on('click', '.btn-edit', function() {
        $('#edit-id').val($(this).data('id'));
        $('#edit-firstname').val($(this).data('firstname'));
        $('#edit-lastname').val($(this).data('lastname'));
        $('#edit-email').val($(this).data('email'));
    });

    // Edit form submit handler
    $('#editMemberForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: location + 'bin/edit.php',
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide');
                $('#menulist').load(location + 'bin/summary_load.php');
                $('#display').fadeIn('slow').html(response).delay(2000).fadeOut('slow');
            }
        });
    });
});
