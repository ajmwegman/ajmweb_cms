$( document ).ready( function () {
	
	var location = '/admin/modules/banners/';

	// make a new has for menu item
	var hash = generate_token(12);
	$("#hash").val(hash);
	
	// start auto save
	var timer;
	$('.autosave').on('keyup change', (function() {  		

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
				url: location + "bin/autosave.php", 
				success: function(result){
					
					$('#loading').fadeIn("fast").html(result).delay(300).fadeOut("fast");
    			}
			});
  		}, 400);
	}));
	
	// start ---> prepare all forms for ajax submission //
	var options = {
		target: '#display',
		success:    function() {
			
			$("#menulist").load(location + "bin/summary_load.php");
			$('#display').fadeIn("slow").delay(2000).fadeOut("slow");
			
		}
	};
	
	$('#menuform').ajaxForm(options);
	// --- end --- //
			// summernote.change
	$('.summernote').on('summernote.change', function(we, contents, $editable) {
		
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
		// start -->  //
	$(".switchbox").on('click', (function(){
		
		var id = $(this).attr("data-set");
		var active = $(this).is(":checked") ? 'y' : 'n' ;
		
		$.ajax({
			type: "post",
			data: { id, active },
			url: location + "bin/activate.php", 
			success: function(result){
	
				$('#loading').fadeIn("fast").html(result).delay(100).fadeOut("fast");
			}
		});
	}));
	// --- end --- //
	
	$(".btn-delete").on('click', (function(){
		
		var id = $('#RowId').val();
		
		//var id = $(this).attr("data-set");
		$.ajax({
			type: "post",
			data: { id, },
			url: location + "bin/delete.php", 
			success: function(result){
				$( "#row" + id).remove();
				$('#display').fadeIn("slow").html(result).delay(2000).fadeOut("slow");
			}
		});
	}));
    
    $(".image-delete").on('click', (function(){
		
		var id = $('#RowId').val();
		
		//var id = $(this).attr("data-set");
		$.ajax({
			type: "post",
			data: { id, },
			url: location + "bin/image_delete.php", 
			success: function(result){
				$( "#row" + id).remove();
				$('#display').fadeIn("slow").html(result).delay(2000).fadeOut("slow");
                
    			$("#cat_image").load(location + "bin/cat_image.php?hash=" + id);

			}
		});
	}));
	
	// start dialog modal //
	$( '#dialogModal' ).on( 'show.bs.modal', function (e) {
		
		$( this ).find( '.btn-ok' ).attr( 'message', $( e.relatedTarget ).data( 'message' ) );
		$('.dialogmessage').html($(this).find('.btn-ok').attr('message') );
		
		$(".btn-ok").on('click', (function(){
			var id = $(this).val();
			$('.RowId').val(id);
		}));
	});
	
    $('.summernote').summernote({
        height: 200,
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']]
  ]
});
    
    /* GERESERVEERD VOOR AFBEELDINGEN UPLOAD */

    /* Upload categorie afbeelding */
        
var id = $('#single_cat_upload').attr('data-set');

$("#single_cat_upload").uploadFile({
		
        url: location + "bin/upload_image.php",
		fileName: "myfile",
		allowedTypes: "jpg,jpeg,gif,png",
		showDelete: false,
		dynamicFormData: function() {
        
            //alert(id);
        var data ={ hash:id }
    	
        return data;
		},
		
		onSuccess:function(files,data,xhr)
		{
			$("#cat_image").load(location + "bin/cat_image.php?hash=" + id);
		}
	});
    
    /* DELETE afbeelding */
    $('#cat_image').on( 'click', 'a#icon-delete', function () {
    	
        var data = $(this).attr('data-button');
            
        var request = $.ajax(
        {
             type: "GET",
             url: location + "bin/delete_img.php?ref=" + data,
             cache: false,
          
             success: function(data)
             {
                  $('#display').fadeIn("slow").html(data).delay(2000).fadeOut("fast");
        
                  // reload the category list
		          $("#cat_image").html("<i class=\"fa fa-file-image-o fa-4x\"></i>");
                 
             }
         });
	} );


	// einde jquery
});

