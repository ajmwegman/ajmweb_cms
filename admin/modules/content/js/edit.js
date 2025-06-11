$(document).ready(function () {

  var location = '/admin/modules/content/';
  // make a new has for menu item
  var hash = generate_token(12);
  $("#hash").val(hash);

  // start auto save
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
	
		// summernote.change
	$('.wysiwyg').on('summernote.change', function(we, contents, $editable) {
		
		//var content = contents;
		
		clearTimeout(timer);

		var id = $(this).attr("data-set");
    	var field = $(this).attr("data-field");
    	var value = contents;
	
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

	});

    $('.wysiwyg').summernote({
        lang: 'nl-NL',
        height: 300,
        callbacks: {
		onImageUpload: function(files, editor, welEditable) {

        var $editor = $(this);
        var $file = files[0];

		data = new FormData();
		data.append("file", $file);//You can append as many data as you want. Check mozilla docs for this
            $.ajax({
				data: data,
                type: "POST",
                url: location + "bin/uploader.php",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                    $($editor).summernote('editor.insertImage', url);
					alert("hoeraa!");
                }
            });
        } //onImageUpload
    }
    });

    $('.dropdown-toggle').dropdown();

// einde jquery
});
