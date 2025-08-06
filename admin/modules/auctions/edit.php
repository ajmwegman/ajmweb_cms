<?php
require_once("src/auction.class.php"); 

// Debug informatie
echo "<!-- Debug: Hash = {$hash} -->";

if(!isset($_GET['id'])) { 
    echo '<div class="alert alert-danger">Geen veiling ID opgegeven.</div>';
    echo '<a href="/admin/auctions/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
} else { 
    $hash = $_GET['id']; 
}

$auction = new auction($pdo);
$result = $auction->getAuction($hash);

if ($result !== false && !empty($result)) {
    // Fetch the first row since there should be only one row with the given ID
    $row = $result[0]; // Assuming that the function returns an array of rows

    $id           = $row['id'];
    $hash         = $row['hash'];
    $productid    = $row['productId'];
    $startdate   = $row['startDate'];
    $enddate     = $row['endDate'];
    $starttime   = $row['startTime'];
    $endtime     = $row['endTime'];
    $minup        = $row['minUp'];
    $commision    = $row['commision'];
    $shippingcost = $row['shippingcost'];
    $startprice = $row['startPrice'];
    $guideprice = $row['guidePrice'];
    $reserveprice = $row['reservePrice'];
    


?>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/auctions/" class="btn btn-dark">Terug</a></div>
		<h2>Veiling <?php echo $auction->productName($productid); ?> bewerken</h2>
	</div>
	
</div>

<div class="row mt-4">
		
	<div class="col-md-12">
		<?php include_once("forms/edit.php"); ?>
	</div>
	
</div>

<?php } else {
    // Handle the case where no auction with the given ID is found
    echo '<div class="alert alert-danger">Veiling niet gevonden voor ID: ' . htmlspecialchars($hash) . '</div>';
    echo '<a href="/admin/auctions/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
} ?>

<script>
    // Get the input elements
    const startDateInput = document.getElementById("startDate");
    const endDateInput = document.getElementById("endDate");

    // Add event listener to the endDate input
    endDateInput.addEventListener("change", function() {
        // Convert the input values to Date objects
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        // Compare the dates
        if (endDate < startDate) {
            // If endDate is earlier than startDate, set it to startDate
            endDateInput.value = startDateInput.value;
        }
    });
</script>