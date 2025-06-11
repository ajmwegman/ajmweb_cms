$(document).ready(function () {
  const searchInput = $("#searchInput");
  const searchResults = $("#searchResults");
  const searchIdInput = $("#searchId");

  var location = '/admin/modules/auctions'; // Define the location variable

  // Functie om een naam te selecteren en in het inputveld te plaatsen
  function selectName(name, productId) {
    searchInput.val(name);
    searchIdInput.val(productId); // Set the selected productId in the hidden field
    searchResults.empty(); // Leeg de zoekresultaten nadat een naam is geselecteerd
    searchResults.hide(); // Verberg de zoekresultaten na het selecteren van een naam
  }

  searchInput.on("input", function () {
    const searchQuery = $(this).val().trim();

    // Voer de zoekopdracht alleen uit als er meer dan 2 letters zijn ingevoerd (aangepast van 3 naar 2)
    if (searchQuery.length >= 2) {
      // Maak een AJAX-verzoek naar search.php om de zoekresultaten op te halen
      $.ajax({
        url: location + "/bin/search.php", // Use the location variable here
        type: "GET",
        data: { searchQuery: searchQuery }, // Stuur de zoekopdracht naar de server via GET-parameters
        dataType: "json",
        success: function (response) {
          // Verwerk de ontvangen JSON-gegevens en toon de zoekresultaten
          if (response && response.length > 0) {
            let resultHtml = "";
            for (let i = 0; i < Math.min(response.length, 5); i++) {
              resultHtml += `<div class="result" data-productid="${response[i].productId}">${response[i].name}</div>`;
            }
            searchResults.html(resultHtml);
            searchResults.show(); // Toon de zoekresultaten
          } else {
            searchResults.hide(); // Verberg de zoekresultaten als er geen resultaten zijn
          }
        },
        error: function (xhr, status, error) {
          // Handel fouten af als het AJAX-verzoek mislukt
          console.log("Error: " + error);
          searchResults.hide(); // Verberg de zoekresultaten bij een fout
        },
      });
    } else {
      searchResults.hide(); // Verberg de zoekresultaten als er minder dan 2 letters zijn ingevoerd (aangepast van 3 naar 2)
    }
  });

  // Event handler om een naam te selecteren bij het klikken op een zoekresultaat
  searchResults.on("click", ".result", function () {
    const selectedName = $(this).text();
    const selectedProductId = $(this).data("productid"); // Use data("productid") instead of data("productId")
    selectName(selectedName, selectedProductId);
  });
});
