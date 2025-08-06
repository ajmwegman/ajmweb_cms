$( document ).ready( function () {
	
	var location = '/admin/modules/customers/';

	// make a new has for menu item
	var hash = generate_token(12);
	$("#hash").val(hash);
	
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

		// start -->  //
	$(".switchbox").on('click', (function(){
		
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
	
    $('#dialogModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Knop die de modal opent
        var message = button.data('message'); // Bericht dat aan de modal moet worden toegevoegd
        var id = button.data('row-id'); // Id van de rij die moet worden verwijderd
        var modal = $(this);

        // Modal titel en bericht instellen
        modal.find('.modal-title').text('Let op!');
        modal.find('.dialogmessage').text(message);

        // Verwijderen knop configureren
        modal.find('.btn-delete').data('row-id', id); // Id van de rij toevoegen aan de verwijderen knop
    });

    // Delete knop functionaliteit
    $(".btn-delete").on('click', function() {
        var id = $(this).data('row-id'); // De id van de rij ophalen
        var row = $('.row[data-row-index="' + id + '"]'); // De rij van de deleteknop vinden met de juiste id

        // Voer hier de rest van je verwijderingslogica uit
    });

	// einde jquery
});

