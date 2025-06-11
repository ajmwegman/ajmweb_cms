<!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
		
      <div class="container">

        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3><?php echo $info['web_naam']; ?></h3>
            <p>
            <?php
                echo (!empty($info['street']) ? $info['street'].' ' : '');
                echo (!empty($info['housenumber']) ? $info['housenumber']."</br>" : '');
                echo (!empty($info['zipcode']) ? $info['zipcode'].' ' : '');
                echo (!empty($info['city']) ? $info['city']."</br>" : '');
                echo (!empty($info['land']) ? $info['land']."</br>" : '');
            ?>*
            </p>              
            <p>      
            <?php
                echo (!empty($info['phonenumber']) ? "<strong>Telefoon: </strong>".$info['phonenumber']."</br>" : '');
                echo (!empty($info['Mobiel']) ? "<strong>Mobiel: </strong>".$info['Mobiel']."</br>" : '');
                echo (!empty($info['std_mail']) ? "<strong>E-mail: </strong>".$info['std_mail']."</br>" : '');
            ?>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Navigatie</h4>
            <ul>
			<?php
			$nav = '';
			foreach($menu as $row => $value) {

				$nav .= '<li><i class="bx bx-chevron-right"></i> <a class="scrollto" href="'.$value['location'].'">'.$value['title'].'</a></li>';
				$nav .= "\n";
			}

			echo $nav;
			?>
            </ul>
          </div>
			
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Handige links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" data-bs-target="#terms">Algemene Voorwaarden</a></li>
              <li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" data-bs-target="#privacy">Privacy Statement</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Sociaal</h4>
            <p>Volg ons online, voor nieuws, korting of aanbiedingen</p>
            <div class="social-links mt-3">
             <!-- <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>-->
              <a href="https://www.facebook.com/" class="facebook"><i class="bx bxl-facebook"></i></a>
             <!-- <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>-->
            </div>
          </div>

        </div>
		  
      </div>
    </div>

    <div class="container py-4">
      <div class="copyright">
        &copy; Copyright <?php echo date('Y'); ?> | <strong><span><?php echo $info['web_naam']; ?></span></strong>.  
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/butterfly-free-bootstrap-theme/ -->
        <a href="https://www.ajmweb.nl">made by ajmweb.nl</a>
      </div>
    </div>
  </footer><!-- End Footer -->
<?php
require_once("terms_and_conditions.php");
require_once("privacystatement.php");
require_once("cookie.php");
?>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="<?php echo $site_location.$theme; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="<?php echo $site_location.$theme; ?>/assets/vendor/purecounter/purecounter.js"></script>
  <script src="<?php echo $site_location.$theme; ?>/assets/vendor/glightbox/js/glightbox.min.js" async ></script>
  <script src="<?php echo $site_location.$theme; ?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="<?php echo $site_location.$theme; ?>/assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo $site_location.$theme; ?>/assets/js/main.js"></script>
<script>
	console.log(document.getElementById("navbarNav"));
console.log(typeof bootstrap.Collapse);

</script>
</body>

</html>