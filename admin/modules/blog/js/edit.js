$(document).ready(function () {
    $('.autosave').on('keyup change', function () {
        var id = $(this).data("set");
        var field = $(this).data("field");
        var value = $(this).val();

        $.post("bin/autosave.php", { id: id, field: field, value: value }, function (result) {
            $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
        });
    });
});
