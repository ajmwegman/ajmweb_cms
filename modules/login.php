<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: /users/index.php");
} else {
?>

<section class="mt-5">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Inloggen</h3>
                        <div id="loginMessage"></div>
                        <form id="loginForm" method="post">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">E-mailadres</label>
                                <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Wachtwoord</label>
                                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Inloggen</button>
                        </form>
                        <p class="mt-3">Nog geen account? <a href="/register.php">Registreer</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/js/login.js"></script>
<?php }
?>
