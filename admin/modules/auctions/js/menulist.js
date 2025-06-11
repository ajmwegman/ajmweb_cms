$( document ).ready( function () {
	
	var location = '/admin/modules/auctions/';

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
	$('.autosave_text').on('summernote.change', function(we, contents, $editable) {
		
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
	
	// start dialog modal //
	$( '#dialogModal' ).on( 'show.bs.modal', function (e) {
		
		$( this ).find( '.btn-ok' ).attr( 'message', $( e.relatedTarget ).data( 'message' ) );
		$('.dialogmessage').html($(this).find('.btn-ok').attr('message') );
		
		$(".btn-ok").on('click', (function(){
			var id = $(this).val();
			$('.RowId').val(id);
		}));
	});
    
     $("#startDate").on("change", function() {
      // Parse de geselecteerde startdatum als een datumobject
      const startDate = new Date($(this).val());

      // Bereken de einddatum door 7 dagen aan de startdatum toe te voegen
      const endDate = new Date(startDate);
      endDate.setDate(startDate.getDate() + 7);

      // Zet de waarde van het einddatumveld
      // Let op: de waarde moet in het "yyyy-MM-dd" formaat zijn voor een date input-veld
      const formattedEndDate = endDate.toISOString().split("T")[0];
      $("#endDate").val(formattedEndDate);
    });
    
    $("#startTime").on("change", function() {
      // Neem de exacte waarde van startTime over naar endTime
      $("#endTime").val($(this).val());
    });
	// einde jquery
});

