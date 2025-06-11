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
			}
		});
	} //onImageUpload
}, // callback
	hint: {
	match: /\b(\w{1,})$/,
	words: function(keyword, callback) {
	$.ajax({
			url: location + "bin/words.php?k=" + keyword,
            type: 'get',
			dataType: "json",
            async: true //This works but freezes the UI
			}).done(callback);
         },
         search: function(keyword, callback) {
            this.words(keyword, callback); //callback must be an array
         },
         content: function(item) {
         	return item;
		 }
	}// end hint
});
	
$('.dropdown-toggle').dropdown();

Sortable.create(menuList, {
	handle: '.drag-handler',
    animation: 450,
    dataIdAttr: 'data-id', // HTML attribute that is used by the `toArray()` method
    store: {
      /*
      * Get the order of elements. Called once during initialization.
      * @param   {Sortable}  sortable
      * @returns {Array}
      */
      get: function (sortable) {
        var order = localStorage.getItem(sortable.options.group.name);
        return order ? order.split('|') : [];
      },
      /*
       * Save the order of elements. Called onEnd (when the item is dropped).
       * @param {Sortable}  sortable
       */
      set: function (sortable) {
        var order = sortable.toArray();
        localStorage.setItem(sortable.options.group.name, order.join('|'));
        $.ajax({
          type: "post",
          data: {
            order
          },
          url: location + "bin/sort.php",
          success: function (result) {

            $('#loading').fadeIn("fast").html(result).delay(1000).fadeOut("fast");
          }
        });
      }
    }
  });

  // start ---> prepare all forms for ajax submission //
  var options = {
    target: '#display',
    success: function () {

      $("#menulist").load(location + "bin/summary_load.php");
      $('#display').fadeIn("slow").delay(2000).fadeOut("slow");

    }
  };

  $('#menuform').ajaxForm(options);
  // --- end --- //

  // start -->  //
  $(".switchbox").on('click', (function () {

    var id = $(this).attr("data-set");
    var status = $(this).is(":checked") ? 'y' : 'n';

    $.ajax({
      type: "post",
      data: {
        id,
        status
      },
      url: location + "bin/activate.php",
      success: function (result) {

        $('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
      }
    });
  }));


  $(".btn-delete").on('click', (function () {

    var id = $('#RowId').val();

    //var id = $(this).attr("data-set");
    $.ajax({
      type: "post",
      data: {
        id,
      },
      url: location + "bin/delete.php",
      success: function (result) {
        $("#row" + id).remove();
        $('#display').fadeIn("slow").html(result).delay(2000).fadeOut("slow");
      }
    });
  }));

  // start dialog modal //
  $('#dialogModal').on('show.bs.modal', function (e) {

    $(this).find('.btn-ok').attr('message', $(e.relatedTarget).data('message'));
    $('.dialogmessage').html($(this).find('.btn-ok').attr('message'));

    $(".btn-ok").on('click', (function () {
      var id = $(this).val();
      $('.RowId').val(id);
    }));
  });

  // einde jquery
});
