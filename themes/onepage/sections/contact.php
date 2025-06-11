 <!-- ======= Contact Section ======= -->
    <div class="contact mb-5">
      <div class="container">
        <div>
			<iframe style="border:0; width: 100%; height: 270px;" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $info['street'];?>%20<?php echo $info['housenumber']; ?>,%20<?php echo $info['city']; ?>&t=&z=11&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" allowfullscreen></iframe>
			
        </div>

        <div class="row mt-5">

          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Locatie:</h4>
                <p><?php echo $info['street']. " ".$info['housenumber']; ?>, <?php echo $info['zipcode']; ?>, <?php echo $info['city']; ?></p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p><?php echo $info['std_mail']; ?></p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Telefoon:</h4>
                <p><?php echo $info['phonenumber']; ?></p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0">

            <form action="bin/contact.php" method="post" role="form" id="contactform" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Naam" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="E-mailadres" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Onderwerp" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" rows="5" placeholder="Bericht" required></textarea>
              </div>
              <div class="text-center mt-3"><button type="submit" class="btn btn-success">Versturen</button></div>
            </form>

          </div>

        </div>

      </div>
    </div><!-- End Contact Section -->