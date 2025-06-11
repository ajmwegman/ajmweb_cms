<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Wachtwoord Herstellen
                </div>
                <div class="card-body">
                    <form action="password_reset.php" method="post">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nieuw Wachtwoord</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Bevestig Wachtwoord</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Wachtwoord Herstellen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
