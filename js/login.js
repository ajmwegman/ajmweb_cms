$(document).ready(function() {
  $("#loginForm").submit(function(event) {
    event.preventDefault();

    // Verkrijg de ingevoerde waarden
    var email = $("#loginEmail").val();
    var password = $("#loginPassword").val();
    var csrf_token = $(this).find('input[name="csrf_token"]').val();

    // Verstuur de gegevens naar de PHP-backend via een AJAX-verzoek
    $.ajax({
      url: "bin/login.php",
      type: "POST",
      data: {
        email: email,
        password: password,
        csrf_token: csrf_token
      },
      dataType: "json", // Verwacht een JSON-response van de server
      success: function(data) {
        // Toon het bericht op basis van de successtatus en leid de gebruiker door naar /users/index.php (als succesvol)
        if (data.success) {
          $("#loginMessage").html("<div class='alert alert-success'>" + data.message + "</div>");
          setTimeout(function() {
            window.location.href = "/users/index.php";
          }, 2000); // Wacht 2 seconden voordat door te verwijzen naar /users/index.php
        } else {
          $("#loginMessage").html("<div class='alert alert-danger'>" + data.message + "</div>");
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        // Toon een foutmelding indien nodig
        $("#loginMessage").html("<div class='alert alert-danger'>Er is een probleem opgetreden bij het inloggen.</div>");
      }
    });
  });
});
