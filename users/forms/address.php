<section class="col-8 mx-auto">
    <div class="row">
        <div class="col">
            <a class="btn btn-primary" href="/users/index.php">Terug</a>
        </div>
    </div>
    <form action="" method="post" id="addressform" name="addressform" enctype="multipart/form-data">
        <div class="text-center mt-2">
            <h3>Adres gegevens</h3>
        </div>
        <input type="hidden" name="userid" value="<?= isset($userId) ? htmlspecialchars($userId) : ''; ?>">
        <div class="card card-body">
            <!-- Bedrijfsnaam -->
            <div class="row">
                <div class="mb-2 col-md-12">
                    <label for="company_name" class="form-label">Bedrijfsnaam</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?= isset($userData['company_name']) ? htmlspecialchars($userData['company_name']) : ''; ?>">
                </div>
            </div>
            <!-- KVK en BTW nummer -->
            <div class="row">
                <div class="mb-2 col-md-6">
                    <label for="kvk_number" class="form-label">KVK-nummer</label>
                    <input type="text" class="form-control" id="kvk_number" name="kvk_number" value="<?= isset($userData['kvk_number']) ? htmlspecialchars($userData['kvk_number']) : ''; ?>">
                </div>
                <div class="mb-2 col-md-6">
                    <label for="vat_number" class="form-label">BTW-nummer</label>
                    <input type="text" class="form-control" id="vat_number" name="vat_number" value="<?= isset($userData['vat_number']) ? htmlspecialchars($userData['vat_number']) : ''; ?>">
                </div>
            </div>
            <!-- Telefoonnummer, Straat en Postcode -->
            <div class="row">
                <div class="mb-2 col-md-12">
                    <label for="phone_number" class="form-label">Telefoonnummer</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= isset($userData['phone_number']) ? htmlspecialchars($userData['phone_number']) : ''; ?>">
                </div>
                <div class="mb-2 col-md-8">
                    <label for="street" class="form-label">Straat</label>
                    <input type="text" class="form-control" id="street" name="street" value="<?= isset($userData['street']) ? htmlspecialchars($userData['street']) : ''; ?>">
                </div>
                <div class="mb-2 col-md-4">
                    <label for="postal_code" class="form-label">Postcode</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= isset($userData['postal_code']) ? htmlspecialchars($userData['postal_code']) : ''; ?>">
                </div>
            </div>
            <!-- Stad en Land -->
            <div class="row">
                <div class="mb-2 col-md-6">
                    <label for="city" class="form-label">Plaats</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?= isset($userData['city']) ? htmlspecialchars($userData['city']) : ''; ?>">
                </div>
                <div class="mb-2 col-md-6">
                    <label for="country" class="form-label">Land</label>
                    <input type="text" class="form-control" id="country" name="country" value="<?= isset($userData['country']) ? htmlspecialchars($userData['country']) : ''; ?>">
                </div>
            </div>
            <!-- Bijwerken Knop -->
            <div class="text-center mt-2">
                <button type="submit" class="btn btn-success mx-auto">Bijwerken</button>
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("addressform").addEventListener("submit", function(event) {
            event.preventDefault(); // Voorkom standaard formulierverschijning

            // Verzamel de formuliergegevens
            var formData = new FormData(this);

            // Specificeer de URL voor de PHP-verwerkingspagina
            var url = "bin/update_factuur_address.php";

            // Voer de AJAX-aanvraag uit
            var xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    // Trim en controleer of de respons de verwachte tekst bevat
                    var trimmedResponse = response.trim();
                    var isSuccess = trimmedResponse.includes("Gegevens zijn bijgewerkt of ingevoegd.");

                    var alertType = isSuccess ? 'alert-success' : 'alert-danger';
                    var message = isSuccess ? "Gegevens zijn bijgewerkt of ingevoegd." : "Er is een fout opgetreden: " + trimmedResponse;

                    document.getElementById("display").innerHTML = "<div class='alert " + alertType + "'>" + message + "</div>";

                    if (isSuccess) {
                        // Stel een timer in om de pagina na 2 seconden te herladen
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                } else {
                    // Toon de foutmelding
                    document.getElementById("display").innerHTML = "<div class='alert alert-danger'>Fout: " + xhr.statusText + "</div>";
                }
            };
            xhr.onerror = function() {
                // Toon de foutmelding
                document.getElementById("display").innerHTML = "<div class='alert alert-danger'>Er is een fout opgetreden bij de netwerkverbinding.</div>";
            };
            xhr.send(formData);
        });
    });
</script>
