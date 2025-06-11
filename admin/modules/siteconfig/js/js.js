$(document).ready(function () {

  var location = '/admin/modules/siteconfig/';
  // make a new has for menu item
 
  var timer;
  $('.autosave').on('keyup change', (function () {

    clearTimeout(timer);
  
	var id = $(this).attr("data-set");
    var field = $(this).attr("data-field");
    var value = $(this).val();

    timer = setTimeout(function () {

      $.ajax({
        type: "post",
        data: {
          id: id,
          field: field,
          value: value,
        },
        url: location + "bin/autosave.php",
        success: function (result) {

          $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
        }
      });
    }, 400);
  }));
	
    
    $('.autosave_config_site').on('keyup change', (function () {

        clearTimeout(timer);
  
	var id = $(this).attr("data-set");
    var field = $(this).attr("data-field");
    var value = $(this).val();

    timer = setTimeout(function () {

      $.ajax({
        type: "post",
        data: {
          id: id,
          field: field,
          value: value,
        },
        url: location + "bin/autosave_config_site.php",
        success: function (result) {
          $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
        }
      });
    }, 400);
  }));
    
    $('.autosave_config').on('keyup change', (function () {

        clearTimeout(timer);
  
	var id = $(this).attr("data-set");
    var field = $(this).attr("data-field");
    var value = $(this).val();

    timer = setTimeout(function () {

      $.ajax({
        type: "post",
        data: {
          id: id,
          field: field,
          value: value,
        },
        url: location + "bin/autosave_config.php",
        success: function (result) {
          $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
        }
      });
    }, 400);
  }));
    
    // checkbox 
    $("[name='area_box']").on('change', function() {

        clearTimeout(timer);

        var sid = $("#sid").val();

        timer = setTimeout(function () {

            $.ajax({
                type: "POST",
                url: location + "bin/update_list.php",
                data: {
                    id: sid,
                    values: $("#form").serializeArray(),
                },                
                success: function(result) {
                $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
                }
            });
        }, 1200);
    });

// --- end --- //
});
