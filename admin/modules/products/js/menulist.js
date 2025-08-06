$( document ).ready( function () {
	
	var location = '/admin/modules/products/';

	// make a new has for menu item
	var hash = generate_token(12);
	$("#hash").val(hash);
	
    Sortable.create(menuList, {
		handle: '.drag-handler',
		animation: 450,
		dataIdAttr: 'data-id', // HTML attribute that is used by the `toArray()` method
		store: {
		/**
		 * Get the order of elements. Called once during initialization.
		 * @param   {Sortable}  sortable
		 * @returns {Array}
		 */
		get: function (sortable) {
			var order = localStorage.getItem(sortable.options.group.name);
			return order ? order.split('|') : [];
		},

		/**
		 * Save the order of elements. Called onEnd (when the item is dropped).
		 * @param {Sortable}  sortable
		 */
		set: function (sortable) {
			var order = sortable.toArray();
			localStorage.setItem(sortable.options.group.name, order.join('|'));
			$.ajax({
				type: "post",
				data: { order, },
				url: location + "bin/sort.php", 
				success: function(result){
					
					$('#loading').fadeIn("fast").html(result).delay(1000).fadeOut("fast");
					}
				});
			}
		}
	});
    /*
        //afbeeldingen sorteren    
    var grid = document.getElementById('imageContainer');
    Sortable.create(grid, {
        animation: 450,
        onUpdate: function(evt) {
            // Verzamelen van de nieuwe volgorde
            var order = [];
            var items = grid.querySelectorAll(".sortable-item");  // Verander .sortable-item naar de juiste klasse
            items.forEach(function(item, index){
                order.push(item.getAttribute("data-set")); // Haal het unieke id uit het data-set attribuut
            });

            // Versturen van de nieuwe volgorde via AJAX
            $.ajax({
                url: location + 'bin/image_sort.php', // jouw PHP-bestand
                type: 'POST',
                data: { order: order }, // Noteer de veranderde data structuur
                success: function(response) {
                    console.log("Succes: ", response);
                },
                error: function(error){
                    console.log("Fout: ", error);
                }
            });
        }
    });
    */
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
    
$(".image-delete").on('click', function() {
    var hash = $('#RowId').val();

    $.ajax({
        type: "post",
        data: { hash },
        url: location + "bin/image_delete.php",
        success: function(result) {
            $("#imageBlock" + hash).fadeOut(500, function() { $(this).remove(); }); // Verwijder het blok met een fade-out over 1 seconde
            $('#display').fadeIn("slow").html(result).delay(1000).fadeOut("slow");
        }
    });
});


    
$(".btn-danger").on('click', function() {
    var id = $(this).find('.bi-trash').attr('data-set'); // Vind het <i>-element binnen de aangeklikte knop
    var hash = $(this).val();
    
    // Zet de waarden in de modal
    $('#RowId').val(id);
    $('.bi-trash').attr('data-set', hash);
});

    
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
		fileName: "myfile[]",
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

// rotate images
    
  // Function to rotate an image
    function rotateImage(image, angle) {
        $(image).css({
            'transform': 'rotate(' + angle + 'deg)',
            'transition': 'transform 0.5s ease'
        });
    }

function sendRotationRequest(angle, imageId) {
    $.ajax({
        url: location + "bin/rotation_script.php", // Verander dit naar het pad van je PHP-script
        type: 'POST',
        data: {
            'angle': angle,
            'imageId': imageId
        },
        success: function(response) {
            console.log('Afbeelding geroteerd: ', response);
        },
        error: function(error) {
            console.log('Error: ', error);
        }
    });
}
// Rotate left
$('.rotate-left').click(function() {
    var image = $(this).closest('.col-md-2').find('img');
    var currentAngle = parseInt($(image).data('rotation'));
    var newAngle = currentAngle - 90;
    $(image).data('rotation', newAngle);
    rotateImage(image, newAngle);

    var imageId = $(this).data('image-id');
    sendRotationRequest(-90, imageId); // Stuur altijd -90 graden naar de server
});

// Rotate right
$('.rotate-right').click(function() {
    var image = $(this).closest('.col-md-2').find('img');
    var currentAngle = parseInt($(image).data('rotation'));
    var newAngle = currentAngle + 90;
    $(image).data('rotation', newAngle);
    rotateImage(image, newAngle);

    var imageId = $(this).data('image-id');
    sendRotationRequest(90, imageId); // Stuur altijd 90 graden naar de server
});

	// einde jquery
});

