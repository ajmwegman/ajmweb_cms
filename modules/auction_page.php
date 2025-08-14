<?php
require_once( "src/auction.class.php" );
$auction = new Auction( $pdo ); // Create an instance of the auction class

#settings
$collumns = 2;
$location = '/product_images/';

$numCollumns = 12 / $collumns;

if ( isset( $_GET[ 'p' ] ) ) {
  // Sanitize the input
    
  $seoTitle = filter_input( INPUT_GET, 'p',  FILTER_SANITIZE_FULL_SPECIAL_CHARS );
  // Use $hash for whatever you need
}

$productData = $auction->getProductBySeoTitle( $seoTitle );

// Loop through the auctions and create grid items
if ( $productData ) {
  $productId = $productData[ 'id' ];

  $auctionData = $auction->getAuctionData( $productId );

  $highestBid = $auction->getHighestBid( $productId );
   
  $startDate    = $auctionData[ 'startDate' ];
  $endDate      = $auctionData[ 'endDate' ];
  $startTime    = $auctionData[ 'startTime' ];
  $endTime      = $auctionData[ 'endTime' ];
  $startPrice   = $auctionData[ 'startPrice' ];

  // Fetch the product data from the 'group_products' table based on productId
  $product = $auction->getProductData( $productId );
  
     /* echo "<pre>";
      print_r($product);
      echo "</pre>";
     */
  $title = $product[ 'title' ];
  $seoTitle = $product[ 'seoTitle' ];
  $price = $product[ 'price' ];
  $description = $product[ 'description' ];
  $image = $product[ 'image' ];
  ?>

<section class="mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h3><?php echo $title; ?></h3>
       
          <?php /*AUCTON GALLERY */ ?>
        <div class="gallery">
          <div class="row gallery-container">
            <?php
            foreach ($product['images'] as $index => $imageName) {
              $columnClass = ($index === 0) ? 'col-lg-12' : 'col-lg-4';
            ?>
              <div class="<?php echo $columnClass; ?> gallery-item <?php echo 'Afbeelding ' . ($index + 1); ?>">
                <a href="<?php echo $location . $imageName; ?>" data-gallery="imageGallery" class="gallery-lightbox preview-link" title="<?php echo 'Afbeelding ' . ($index + 1); ?>">
                  <img src="<?php echo $location . $imageName; ?>" class="img-fluid" alt="<?php echo 'Afbeelding ' . ($index + 1); ?>">
                </a>
              </div>
              <?php
              if ($index === 0) {
                echo "</div>\n";
                echo '<div class="row ">';
                  echo "\n";
              }
            }
            ?>
        </div>
      </div>

        <div class="mt-4">
        <h3>Omschrijving</h3>
            <p><?php echo $description; ?></p>
        </div>      
          
      </div>
        
        <?php /* RECHTERZIJDE VEILING MODULE */ ?>
      <div class="col-md-5">
        <div id="display"></div>
        <div id="message-alert"></div>
        <?php echo "Lotid: ". $productId; ?>
        <div class="card">
          <div class="card-body"> 
            <!-- Add PHP echo statements for product id and end date and time -->
            <h3 class="clock" id="<?php echo $productId; ?>" countdown="<?php echo $endDate.' '.$endTime; ?>"></h3>
            <hr>
            <h3>Huidig Bod: <span id="highestBid">Loading...</span></h3>
          </div>
        </div>
                            <input type="hidden" value="<?php echo $productId; ?>" name="lotid" id="lotid">

          
<?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>
        <form action="" method="post" name="form" class="form form-inline">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

          <div class="mb-3 mt-4">
            <label for="bid" class="form-label">Doe een bod</label>
            <div class="input-group">
              <input type="text" value="" name="bid" class="form-control" placeholder="100,00" id="bid">
              <span class="input-group-text">&euro;</span> </div>
          </div>
       

        <button type="submit" class="btn btn-success">Plaats bod</button>
 </form>
 
<button id="fav-btn" class="btn">
        <i id="fav-icon" class="bi bi-heart"></i>
    </button>

          
          <?php } else { 
          $_SESSION['refpage'] = $_SERVER['HTTP_HOST'];
          ?>
            <div class="card card-body mt-4">
               <a href="/login.php" class="btn btn-success">Login en bied mee</a>
            </div>
          <?php } ?>     
          
        <?php /* Our message container. data-counter should contain initial value of counter from the database */ ?>
        <div class="card mt-4">
          <div class="container">
            <div id="message-list">Plaats als eerste een bod!</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php } ?>
<script>
    var baseUrl = '<?php echo "//" . $_SERVER['HTTP_HOST']; ?>';
    
    $(document).ready(function() {
    
    var lotid = $('#lotid').val();
        
function checkFavStatus() {
    $.ajax({
        url: baseUrl + '/modules/auction/check_fav_status.php',
        type: 'GET',
        data: { product_id: lotid },
        dataType: 'json',
        success: function(isFav) {
            if(isFav) {
                $('#fav-icon').removeClass('bi-heart').addClass('bi-heart-fill fav-red');
            } else {
                $('#fav-icon').removeClass('bi-heart-fill fav-red').addClass('bi-heart');
            }
        },
        error: function(xhr, status, error) {
            console.error('Fout bij het controleren van de favorietenstatus:', error);
        }
    });
}


    // Roep deze functie aan bij het laden van de pagina
    checkFavStatus();
$('#fav-btn').click(function() {
    // AJAX-aanvraag voor update_favs.php
    $.ajax({
        url: baseUrl + '/modules/auction/update_favs.php',
        type: 'POST',
        data: { product_id: lotid, csrf_token: $('input[name="csrf_token"]').val() },
        dataType: 'json',
        success: function(response) {
            //console.log("AJAX-aanroep geslaagd", response); // Controleer de respons van de AJAX-aanroep
            if(response.status === 'added') {
                $('#fav-icon').removeClass('bi-heart').addClass('bi-heart-fill fav-red');
            } else if(response.status === 'removed') {
                $('#fav-icon').removeClass('bi-heart-fill fav-red').addClass('bi-heart');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX request error:', error); // Log eventuele fouten in de console
        }
    });
});

 
    var EventSourceForAuctionBids = new EventSource(baseUrl + '/modules/auction/checker.php?lotid=' + lotid);

    EventSourceForAuctionBids.addEventListener('message', function(event) {
        var eventData = JSON.parse(event.data);

        if (eventData.bids) {
            // Handle bid history updates
            var bidHistoryData = eventData.bids;
            displayBidHistory(bidHistoryData);
        }

        if (eventData.highestBid) {
            // Handle highest bid updates
            var newHighestBid = parseFloat(eventData.highestBid);
            $('#highestBid').text(newHighestBid.toFixed(2));
        }
    }, false);

    function displayBidHistory(bidHistoryData) {
        var bidHistoryHTML = '';
        // Loop through the bid history data and create HTML for each bid
        bidHistoryData.forEach(function(bid) {
            bidHistoryHTML += '<div class="row">';
            bidHistoryHTML += '<div class="col-md-3 h5">' + bid.userid + '</div>';
            bidHistoryHTML += '<div class="col"><small>' + bid.timestamp + '</small></div>';
            bidHistoryHTML += '<div class="col-md-3 h5">' + bid.bid + '</div>';
            bidHistoryHTML += '</div>';
            bidHistoryHTML += '<hr>';
        });
        
    $('#message-list').html(bidHistoryHTML);
    }
      
            $('form[name="form"]').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var bidAmount = $('#bid').val();
            /*var lotid = $('#lotid').val();*/
            var csrf_token = $('input[name="csrf_token"]').val();
            var data = {
                bid: bidAmount,
                lotid: lotid,
                csrf_token: csrf_token
            };

            $.ajax({
                url: baseUrl + '/modules/auction/addbid.php',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#display').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#display').fadeIn(300); // Fade in over 0.3 seconden

                                   // Check if the new bid is higher than the current highest bid
            var newBid = parseFloat(bidAmount);
            var currentHighestBid = parseFloat(response.highest_bid);

            if (newBid > currentHighestBid) {
                // Update the displayed highest bid
                $('#highest-bid').text(newBid);

                // You might also want to update the highest bid in the response
                response.highest_bid = newBid;
            }
                        
                        setTimeout(function() {
                            $('#display').fadeOut(300, function() {
                                $(this).html('');
                            });
                        }, 2000);
                    } else if (response.status === 'error') {
                        var errorMessages = response.message.split('<br>');
                        var errorMessageHTML = '<div class="alert alert-danger"><strong>Let op!</strong><br>';

                        errorMessages.forEach(function(message) {
                            errorMessageHTML += message + '<br>';
                        });

                        errorMessageHTML += '</div>';
                        $('#display').html(errorMessageHTML);
                        $('#display').fadeIn(300); // Fade in over 0.3 seconden

                        setTimeout(function() {
                            $('#display').fadeOut(300, function() {
                                $(this).html('');
                            });
                        }, 4000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request error:', error);
                }
            });
        });

        // Function to disable the form elements
        function disableFormElements() {
            $('input[name="bid"]').prop('disabled', true);
            $('button[type="submit"]').prop('disabled', true);
        }

        // Function to check if the auction has expired
        function checkAuctionExpiry() {
            var endDate = <?php echo json_encode($endDate); ?>; // Replace this with the actual end date of the auction
            var endTime = <?php echo json_encode($endTime); ?>;  // Replace this with the actual end time of the auction

            var currentDateTime = new Date();
            var endDateTime = new Date(endDate + ' ' + endTime);

            if (currentDateTime > endDateTime) {
                // Auction has expired, disable the form elements
                disableFormElements();
            }
        }

        // Check the auction expiry when the page loads
        checkAuctionExpiry();

        // Periodically check the auction expiry in real-time
        setInterval(function() {
            checkAuctionExpiry();
        }, 1000); // 1000 milliseconds (1 second) interval

        // Attach a submit event handler to the form
        $('form[name="form"]').submit(function(e) {
            // Check the auction expiry before submitting the form
            checkAuctionExpiry();

            // If the auction has expired, prevent the form submission
            if ($('input[name="bid"]').is(':disabled')) {
                e.preventDefault();
                alert('De veiling is afgelopen. U kunt geen bod meer plaatsen.');
            }
        });
    });

    // Function to update the countdown for a specific lot
    function updateCountdown(element, targetDate) {
    var now = new Date().getTime();
    var distance = new Date(targetDate).getTime() - now;
    var lastThreeMinutes = 180000; // 3 minuten in milliseconden

    var updateInterval = (distance <= lastThreeMinutes) ? 30000 : 1000; // Update elke 30 sec in de laatste 3 min

    var countdownInterval = setInterval(function() {
        now = new Date().getTime();
        distance = new Date(targetDate).getTime() - now;

        if (distance <= 0) {
            clearInterval(countdownInterval);
            element.innerHTML = "Deze kavel is gesloten.";
            $('input[name="bid"]').prop('disabled', true);
            $('button[type="submit"]').prop('disabled', true);
            return;
        }

        // Bereken dagen, uren, minuten en seconden
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Toon de aftelling
        if (days === 0 && hours === 0 && minutes === 0) {
            element.innerHTML = "Sluit over: " + seconds + "s";
        } else {
            element.innerHTML = "Sluit over: " + days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        }

        // Update het interval wanneer het in de laatste 3 minuten komt
        if (distance <= lastThreeMinutes && updateInterval !== 30000) {
            clearInterval(countdownInterval);
            updateInterval = 30000;
            updateCountdown(element, targetDate);
        }

    }, updateInterval);
}

// Initialiseer de aftelling voor elk element met de class 'clock'
var countdownElements = document.querySelectorAll('.clock');
countdownElements.forEach(function(element) {
    var targetDate = element.getAttribute('countdown');
    updateCountdown(element, targetDate);
});

</script> 
