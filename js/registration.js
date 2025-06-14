$(document).ready(function() {
  $("#registrationForm").submit(function(event) {
    event.preventDefault();

    // Verkrijg de ingevoerde waarden
    var email = $("#email").val();
    var password = $("#password").val();

    // Verstuur de gegevens naar de PHP-backend via een AJAX-verzoek
    $.ajax({
      url: "bin/registration.php",
      type: "POST",
      data: {
        email: email,
        password: password
      },
      dataType: "json", // Verwacht een JSON-response van de server
      success: function(data) {
        // Toon het bericht op basis van de successtatus en leid de gebruiker door naar /users/index.php (als succesvol)
        if (data.success) {
          $("#registrationMessage").html("<div class='alert alert-success'>" + data.message + "</div>");
          setTimeout(function() {
            window.location.href = "/users/index.php";
          }, 2000); // Wacht 2 seconden voordat door te verwijzen naar /users/index.php
        } else {
          $("#registrationMessage").html("<div class='alert alert-danger'>" + data.message + "</div>");
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        // Toon een foutmelding indien nodig
        $("#registrationMessage").html("<div class='alert alert-danger'>Er is een probleem opgetreden bij het registreren.</div>");
      }
    });
  });
});
