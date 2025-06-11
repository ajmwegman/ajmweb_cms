$(document).ready(function () {

  var location = '/admin/modules/config/';
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
	
// start -->  //
	$(".activate").on('click', (function(){
		
		var id = $(this).attr("data-set");
		var status = $(this).is(":checked") ? 'y' : 'n' ;
		
		$.ajax({
			type: "post",
			data: { id, status },
			url: location + "bin/activate.php", 
			success: function(result){
	
				$('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
			}
		});
	}));
	// --- end --- //
    
    // start -->  //
$(".save_lang").click(function(){
  var checkboxes = $(".save_lang:checked");
  var checkboxValues = [];
  checkboxes.each(function() {
    checkboxValues.push($(this).val());
  });
  $.ajax({
    type: 'POST',
    url: location + "bin/languages.php", 
    data: {checkboxes: checkboxValues},
    success: function(response) {
      // handle server response
        $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
    },
    error: function(xhr, status, error) {
      // handle error
    }
  });
});

	// --- end --- //
    
});
