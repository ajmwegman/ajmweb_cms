<div class="container mt-5">
    <form id="nieuwsbriefForm">
        <div class="row">
            [NIEUWSBRIEF]<br>
            [NIEUWSBRIEF_SLOGAN]
            
            <div class="col-md-6 mb-3">
                <input type="text" class="form-control" id="voornaam" name="voornaam" placeholder="Voornaam" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" class="form-control" id="achternaam" name="achternaam" placeholder="Achternaam" required>
            </div>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="emailadres" name="emailadres" placeholder="Emailadres" required>
        </div>
        <button type="submit" class="btn btn-primary">Inschrijven</button>
    </form>
</div>

<script>
    $("#nieuwsbriefForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'bin/newsletter_subscribe.php',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
            }
        });
    });
</script>