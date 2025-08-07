<?php
require_once( "template/config.php" );
require_once( "template/head.php" );

// Auto-login check for remember me functionality
// session_start() is already called in config.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== 'yes') {
    if (isset($_COOKIE['remember_me'])) {
        include( "bin/login_check.php" );
        // If auto-login successful, redirect to admin
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'yes') {
            header('Location: /admin/index.php');
            exit;
        }
    }
}
?>
<body>

<div id="display" class="alert-fixed"></div>

<form action="bin/login.php" method="post" name="form" id="form">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
				  <div class="text-center my-5">
					<img src="assets/images/logo_c.png" alt="" class="img-fluid"/>
                    </div>
					<div class="card shadow-lg">
						<div class="card-body p-5">
							<h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
								<div class="mb-3">
									<label class="mb-2 text-muted" for="email">Gebruikersnaam</label>
									<input id="username" type="text" class="form-control" name="username" value="" required autofocus>
								</div>

								<div class="mb-3">
									<div class="mb-2 w-100">
										<label class="text-muted" for="password">Wachtwoord</label>
										<a href="forgot.php" class="float-end">
											Wachtwoord vergeten?
										</a>
									</div>
									<input id="password" type="password" class="form-control" name="password" required>
								</div>

								<div class="d-flex align-items-center">
									<div class="form-check">
										<input type="checkbox" name="remember" id="remember" class="form-check-input">
										<label for="remember" class="form-check-label">Onthoud mij</label>
									</div>
									<button type="submit" class="btn btn-dark ms-auto">
										Login
									</button>
								</div>
						</div><!--
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Don't have an account? <a href="register.html" class="text-dark">Create One</a>
							</div>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<script src="/admin/js/jquery.form.js"></script> 
<script src="/admin/js/js.js"></script> 

<script>
// Remember Me functionality with localStorage
document.addEventListener('DOMContentLoaded', function() {
    const rememberCheckbox = document.getElementById('remember');
    const usernameInput = document.getElementById('username');
    
    // Load saved data from localStorage
    const savedRemember = localStorage.getItem('rememberMe');
    const savedUsername = localStorage.getItem('savedUsername');
    
    if (savedRemember === 'true') {
        rememberCheckbox.checked = true;
    }
    
    if (savedUsername) {
        usernameInput.value = savedUsername;
    }
    
    // Save data when form is submitted
    document.getElementById('form').addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            localStorage.setItem('rememberMe', 'true');
            localStorage.setItem('savedUsername', usernameInput.value);
        } else {
            localStorage.removeItem('rememberMe');
            localStorage.removeItem('savedUsername');
        }
    });
    
    // Update localStorage when checkbox changes
    rememberCheckbox.addEventListener('change', function() {
        if (this.checked) {
            localStorage.setItem('rememberMe', 'true');
        } else {
            localStorage.removeItem('rememberMe');
        }
    });
    
    // Update saved username when input changes
    usernameInput.addEventListener('input', function() {
        if (rememberCheckbox.checked) {
            localStorage.setItem('savedUsername', this.value);
        }
    });
});
</script>

<?php require_once("template/footer.php"); ?>

