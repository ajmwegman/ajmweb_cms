$(document).ready(function () {

  const location = '/admin/modules/config/';
  
  // Autosave functionality
  let autosaveTimer;
  $('.autosave').on('keyup change', (function () {

    clearTimeout(autosaveTimer);

    const id = $(this).attr("data-set");
    const field = $(this).attr("data-field");
    const value = $(this).val();

    autosaveTimer = setTimeout(() => {

      $.post(location + "bin/autosave.php", {
        id: id,
        field: field,
        value: value,
      }, function (result) {

        $('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
      }, "html");
    }, 400);
  }));
  
  // Activate functionality
  $(".activate").on('click', (function(){
    
    const id = $(this).attr("data-set");
    const status = $(this).is(":checked") ? 'y' : 'n' ;
    
    $.post(location + "bin/activate.php", { id, status }, function(result){
  
      $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
    }, "html");
  }));

  // Save language functionality
  $(".save_lang").click(function(){
    const checkboxes = $(".save_lang:checked");
    const checkboxValues = checkboxes.map(function() {
      return $(this).val();
    }).get();
    
    $.post(location + "bin/languages.php", {checkboxes: checkboxValues}, function(response) {
      // handle server response
      $('#loading').fadeIn("fast").html(response).delay(100).fadeOut("fast");
    }, "html")
    .fail(function(xhr, status, error) {
      // handle error
    });
  });

});