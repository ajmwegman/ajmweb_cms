<?php
require_once( "template/config.php" );
require_once( "template/head.php" );
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

<?php require_once("template/footer.php"); ?>

