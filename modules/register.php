<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: /users/index.php");
    exit();
}
?>
<section class="mt-5">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Registratie</h3>
                        <div id="registrationMessage"></div>
                        <form id="registrationForm" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mailadres</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Wachtwoord</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registreren</button>
                        </form>
                        <p class="mt-3">Al een account? <a href="/login.php">Inloggen</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/js/registration.js"></script>
?>
