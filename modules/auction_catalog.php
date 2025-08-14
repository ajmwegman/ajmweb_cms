<section class="container mt-5">
    <div class="row">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <div class="col-md-3">
<?php
$uniqueCategories = $auction->getUniqueCategories(); // Aangenomen dat $auction je object is

echo '<ul class="list-group">';
echo '<li class="list-group-item category-button show-all" data-target-category="all">Toon alles</li>';

foreach ($uniqueCategories as $category) {
    echo '<li class="list-group-item category-button" data-target-category="' . htmlspecialchars($category) . '">' . htmlspecialchars($category) . '</li>';
}
echo '</ul>';
?>
        </div>
                
        <div class="col-md-9">
                
        <div class="row row-cols-1 row-cols-md-4 g-4">
<?php

            $auctions = $auction->getAuctions(); // Use the getAuctions() method to fetch auction data

            // Loop through the auctions and create grid items
            $counter = 0;
            foreach ($auctions as $auctionData) {
                $productId = $auctionData['productId'];
                $startDate = $auctionData['startDate'];
                $endDate = $auctionData['endDate'];
                $startTime = $auctionData['startTime'];
                $endTime = $auctionData['endTime'];
                $startPrice = $auctionData['startPrice'];
                $numbids = $auctionData['numbids'];
                $guideprice = $auctionData['guidePrice'];
                $reserveprice = $auctionData['reservePrice'];

                // Fetch the product data from the 'group_products' table based on productId
                $product = $auction->getProductData($productId);

                $title = $product['title'];
                $seoTitle = $product['seoTitle'];
                $price = $product['price'];
                $description = $product['description'];
                $image = $product['image'];
                ?>

                <div class="col-md-4 mt-4" data-category="<?php echo htmlspecialchars($product['category']); ?>">
                    <div class="card">
                        <?php
                
                        // Check if the 'images' key exists in the $product array and if there are any images
                        if (isset($product['images']) && !empty($product['images'])) {
                            // Loop through the images and display them
                            $firstImage = reset($product['images']);

                                echo '<img src="/product_images/' . $firstImage . '" class="card-img-top" alt="' . $title . '">';
                            
                        } else {
                            // If there are no images, display a default image
                            echo '<img src="/path/to/default-image.jpg" class="card-img-top" alt="' . $title . '">';
                        }
                
                        $highestBid = $auction->getHighestBid( $productId );

                        ?>
                        <div class="card-body">
                            <a href="/veiling/<?php echo $seoTitle; ?>/" class="text-dark">
                            <div class="row">
                                <div class="col">Kavel: <?php echo $productId; ?></div>
                                <div class="col text-end"><?php echo $numbids; ?> Biedingen</div>
                            </div>
                            <div class="row">
                                <h5 class="card-title"><?php echo $title; ?></h5>
                                <p>Richtprijs: € <?php echo ($guideprice < 1) ? "-" : $guideprice; ?><br>
                                Huidig bod: € <?php echo $highestBid; ?></p>
                            </div>
                             </a>
                               <div class="row">
                                <div class="col-2">
                                   <button class="fav-btn" data-product-id="<?php echo $product['id']; ?>">
        <i class="fav-icon bi bi-heart"></i>
    </button>                                   
                                   </div>
                                <div class="col-10 clock text-end" id="<?php echo $productId; ?>" countdown="<?php echo $endDate; ?> <?php echo $endTime; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Increase the counter
                $counter++;

                // If the counter is divisible by 4, start a new row
                if ($counter % 3 == 0) {
                   // echo '</div><div class="row">';
                }
            }
            ?>
        </div>
    </div>
</div>
</section>
<script>
var baseUrl = '<?php echo "//" . $_SERVER['HTTP_HOST']; ?>';

$(document).ready(function() {
    $('.fav-btn').each(function() {
        checkFavStatus($(this));
    });
});
    
 window.addEventListener('load', () => {
  let productContainer = document.querySelector('.row-cols-1');
  if (productContainer) {
    let productIsotope = new Isotope(productContainer, {
      itemSelector: '.col-md-4',
      layoutMode: 'fitRows'
    });

    let categoryButtons = document.querySelectorAll('.category-button');

    categoryButtons.forEach((button) => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        categoryButtons.forEach((el) => {
          el.classList.remove('filter-active');
        });
        
        this.classList.add('filter-active');
        
        let filterValue = this.getAttribute('data-target-category') === 'all' ? '*' : `[data-category='${this.getAttribute('data-target-category')}']`;
        
        productIsotope.arrange({
          filter: filterValue
        });
      });
    });
    
    // De "Toon alles" knop
    document.querySelector('.show-all').addEventListener('click', function(e) {
      e.preventDefault();
      productIsotope.arrange({
        filter: '*'
      });
    });
  }
});


// Function to update the countdown for a specific lot
function updateCountdown(element, targetDate) {
    // Update the countdown every 1 second
    var countdownInterval = setInterval(function() {
        // Get the current date and time
        var now = new Date().getTime();
        // Calculate the remaining time in milliseconds
        var distance = new Date(targetDate).getTime() - now;
        // Check if the countdown is finished
        if (distance <= 0) {
            clearInterval(countdownInterval);
            element.innerHTML = "Deze kavel is gesloten.";
            return;
        }

        // Calculate days, hours, minutes, and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the countdown for this lot
        if (days === 0 && hours === 0 && minutes === 0) {
            element.innerHTML = seconds + "s";
        } else {
            element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        }
    }, 1000);
}

// Get all elements with class "clock"
var countdownElements = document.querySelectorAll('.clock');

    // Loop through each countdown element and start the countdown
countdownElements.forEach(function(element) {
    var targetDate = element.getAttribute('countdown');
    updateCountdown(element, targetDate);
});
    
function checkFavStatus(button) {
    var icon = button.find('.fav-icon'); // Zoek het favorietenpictogram binnen de knop
    var product_id = button.data('product-id'); // Haal het product ID op uit data-attribuut

    $.ajax({
        url: baseUrl + '/modules/auction/check_fav_status.php',
        type: 'GET',
        data: { product_id: product_id },
        dataType: 'json',
        success: function(isFav) {
            if(isFav) {
                icon.removeClass('bi-heart').addClass('bi-heart-fill fav-red');
            } else {
                icon.removeClass('bi-heart-fill fav-red').addClass('bi-heart');
            }
        },
        error: function(xhr, status, error) {
            console.error('Fout bij het controleren van de favorietenstatus:', error);
        }
    });
}

// Verander #fav-btn naar .fav-btn om alle knoppen te selecteren
$('.fav-btn').click(function() {
    var button = $(this); // Selecteer de specifieke knop die is geklikt
    var icon = button.find('.fav-icon'); // Zoek het favorietenpictogram binnen de knop
    var product_id = button.data('product-id'); // Haal het product ID op uit data-attribuut

    // AJAX-aanvraag voor update_favs.php
    $.ajax({
        url: baseUrl + '/modules/auction/update_favs.php',
        type: 'POST',
        data: { product_id: product_id, csrf_token: $('input[name="csrf_token"]').val() },
        dataType: 'json',
        success: function(response) {
            //console.log("AJAX-aanroep geslaagd", response); // Controleer de respons van de AJAX-aanroep
            if(response.status === 'added') {
                icon.removeClass('bi-heart').addClass('bi-heart-fill fav-red');
            } else if(response.status === 'removed') {
                icon.removeClass('bi-heart-fill fav-red').addClass('bi-heart');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX request error:', error); // Log eventuele fouten in de console
        }
    });
});
</script>