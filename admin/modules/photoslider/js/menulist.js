$(document).ready(function () {
    const location = '/admin/modules/photoslider/';

    // make a new has for menu item
	var hash = generate_token(12);
	$("#hash").val(hash);
    
    // Helper functie voor berichten tonen
    function showMessage(target, message, type = 'info', duration = 2000) {
        const alertType = type === 'error' ? 'alert-danger' : 'alert-warning';
        $(target).html(`<div class="alert ${alertType}">${message}</div>`).fadeIn("slow").delay(duration).fadeOut("slow");
    }

    // Functie om de slider dropdown te verversen
    function refreshSliderDropdown() {
        $.ajax({
            type: "GET",
            url: `${location}bin/load_sliders.php`,
            success: function (data) {
                $('#slider').html(data);
            },
            error: function (xhr, status, error) {
                console.error("Error loading sliders:", status, error);
            }
        });
    }

    // AJAX voor het toevoegen van een nieuwe slider
    $('#addSliderForm').off('submit').on('submit', function (e) {
        e.preventDefault();
        const sliderName = $('#sliderName').val().trim();
        if (!sliderName) {
            showMessage('#display_addslider', 'De naam van de slider mag niet leeg zijn!', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: `${location}bin/add_slider.php`,
            data: { slider_name: sliderName },
            success: function (response) {
                showMessage('#display_addslider', response, 'info');
                $('#sliderName').val('');
                setTimeout(() => $('#sliderModal').modal('hide'), 2000);
                refreshSliderDropdown();
            },
            error: function (xhr, status, error) {
                showMessage('#display_addslider', `Er is iets misgegaan! ${error}`, 'error');
            }
        });
    });

    // Debounce functie om frequente aanroepen te optimaliseren
    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Debounced auto-save handler
    const debouncedAutoSave = debounce(function (id, field, value, url) {
        $.ajax({
            type: "post",
            data: { id, field, value },
            url,
            success: function (result) {
                $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
            }
        });
    }, 400);

    // Auto save event handlers
    $(document).on('keyup change', '.autosave', function () {
        const id = $(this).attr("data-set");
        const field = $(this).attr("data-field");
        const value = $(this).val();
        debouncedAutoSave(id, field, value, `${location}bin/autosave.php`);
    });

    $(document).on('keyup change', '.autosave_settings', function () {
        const id = $(this).attr("data-set");
        const field = $(this).attr("data-field");
        const value = $(this).val();
        debouncedAutoSave(id, field, value, `${location}bin/autosave_settings.php`);
    });

    // Modaal event om data-message te tonen
    $('#dialogModal').on('show.bs.modal', function (e) {
        const button = $(e.relatedTarget); // De knop die de modaal opende
        const message = button.data('message'); // Haal de data-message attribuut op
        const rowId = button.val(); // Haal de waarde van de knop (ID) op

        // Vul de modaal met de juiste data
        $(this).find('.dialogmessage').text(message);
        $(this).find('.RowId').val(rowId);
    });

    // Verwijderactie voor de knop in de modaal
    $(document).on('click', '.btn-delete', function () {
        const id = $('.RowId').val();

        $.ajax({
            type: "post",
            data: { id },
            url: `${location}bin/delete.php`,
            success: function (result) {
                $("#row" + id).remove(); // Verwijder de rij uit de DOM
                $('#display').fadeIn("slow").html(result).delay(2000).fadeOut("slow");
            },
            error: function (xhr, status, error) {
                console.error("Error deleting item:", status, error);
            }
        });
    });

    // Generic AJAX handler voor activerings toggles
    function handleToggleClick(selector, url) {
        $(document).on('click', selector, function () {
            const id = $(this).attr("data-set");
            const field = $(this).attr("data-field");
            const name = $(this).is(":checked") ? 'y' : 'n';
            $.ajax({
                type: "post",
                data: { id, field, name },
                url,
                success: function (result) {
                    $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
                }
            });
        });
    }

    // Apply toggle handlers
    handleToggleClick('.switchbox', `${location}bin/activate.php`);
    handleToggleClick('.settings_switchbox', `${location}bin/activate_settings.php`);

    // Initializeer Sortable voor sorteren van items
    Sortable.create(menuList, {
        handle: '.drag-handler',
        animation: 450,
        dataIdAttr: 'data-id',
        store: {
            get: function (sortable) {
                const order = localStorage.getItem(sortable.options.group.name);
                return order ? order.split('|') : [];
            },
            set: function (sortable) {
                const order = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, order.join('|'));
                $.ajax({
                    type: "post",
                    data: { order },
                    url: `${location}bin/sort.php`,
                    success: function (result) {
                        $('#loading').fadeIn("fast").html(result).delay(1000).fadeOut("fast");
                    }
                });
            }
        }
    });

    // Voorbereid de formulieren voor AJAX submission
    $('#menuform').ajaxForm({
        target: '#display',
        success: function () {
            $("#menulist").load(`${location}bin/summary_load.php`);
            $('#display').fadeIn("slow").delay(2000).fadeOut("slow");
        }
    });

    // Initialize Summernote editor
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']]
        ]
    });

    // Upload voor afbeeldingen
    var id = $('#single_cat_upload').attr('data-set');

    $("#single_cat_upload").uploadFile({
        url: `${location}bin/upload_image.php`,
        fileName: "myfile",
        allowedTypes: "jpg,jpeg,gif,png",
        showDelete: false,
        dynamicFormData: function () {
            return { hash: id };
        },
        onSuccess: function (files, data, xhr) {
            $("#cat_image").load(`${location}bin/cat_image.php?hash=${id}`);
        }
    });

    // Verwijderactie voor de knop in de modaal
    $(document).on('click', '.image-delete', function () {
        const id = $('.RowId').val();

        $.ajax({
            type: "GET",
            url: `${location}bin/image_delete.php?hash=${id}`,
            cache: false,
            success: function (data) {
                $('#display').fadeIn("slow").html(data).delay(2000).fadeOut("fast");
                $("#cat_image").html("<i class=\"fa fa-file-image-o fa-4x\"></i>"); // Toon een placeholder of niets
            },
            error: function (xhr, status, error) {
                showMessage('#display', `Er is iets misgegaan bij het verwijderen! ${error}`, 'error');
            }
        });
    });

    // einde jQuery
});
