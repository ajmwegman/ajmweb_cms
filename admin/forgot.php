<?php
require_once( "template/config.php" );
require_once( "template/head.php" );
?>
<body>
    
<div id="display" class="alert-fixed"></div>

<form action="bin/password_recover.php" enctype="multipart/form-data" name="form" id="form" method="post" autocomplete="off">

<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
					<div class="text-center my-5">
                		<img src="assets/images/logo_c.png" alt="" class="img-fluid"/>
					</div>
					<div class="card shadow-lg">
						<div class="card-body p-5">
							<h1 class="fs-4 card-title fw-bold mb-4">Wachtwoord Vergeten</h1>
								<div class="mb-3">
									<label class="mb-2 text-muted" for="email">E-Mailaddres</label>
									<input id="email" type="email" class="form-control" name="email" value="" required autofocus>
								</div>

								<div class="d-flex align-items-center">
									<button type="submit" class="btn btn-dark ms-auto">
										Herstellen	
									</button>
								</div>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Niet vergeten? <a href="login.php" class="text-dark">Login</a>
							</div>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						Copyright &copy; 2002-<?php echo date('Y'); ?> &mdash; Ajmweb.nl 
					</div>
				</div>
			</div>
		</div>
	</section>
</form>
<script src="/admin/js/jquery.form.js"></script> 
<script src="/admin/js/js.js"></script> 

<?php require_once("template/footer.php"); ?>

