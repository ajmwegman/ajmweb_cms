$(document).ready(function() {
    
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.querySelector('.sortable-container'); // De container waarin de afbeeldingen zich bevinden
        var sortable = new Sortable(container, {
            handle: '.img-fluid', // Dit zorgt ervoor dat je alleen kunt slepen bij het vastpakken van de afbeelding
            animation: 150,
            onUpdate: function(evt) {
                // Je kunt hier code toevoegen om de nieuwe volgorde op te slaan, bijvoorbeeld via een AJAX-aanroep
            }
        });
    });
  // Voeg een klikgebeurtenis toe aan pagineringknoppen

    var page = $(this).attr('href').split('=')[1];
    var id = "kwak";
  // Functie om producten voor de opgegeven pagina op te halen via AJAX
  $('.pagination a').on('click', function(e) {
      
    $.ajax({
      url: '/admin/modules/products/bin/summary.php', // Het nieuwe PHP-bestand voor AJAX
      data: { id, page, },
      type: 'post',
      success: function(data) {
        // Wis de huidige producten en toon de nieuwe producten
        //$('#menulist').empty();
        //displayProducts(data.products);

        // Markeer de juiste pagineringknop als actief
        $('.pagination li').removeClass('active');
        $('.pagination a[href="/admin/products/' + page + '/"]').parent().addClass('active');
      },
      error: function() {
        console.log('Fout bij het laden van producten.');
      }
    });
  })
});