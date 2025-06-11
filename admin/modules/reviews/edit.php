<?php
require_once("src/review.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

$review = new review($pdo);	

$result = $review->getReview( $id );

  	foreach ( $result as $data => $row ) {	
		
		$id 		  = $row['id'];
		$hash 		  = $row['hash'];
		$subject	  = $row['subject'];
		$location     = $row['location'];
		$reviewdate	  = $row['reviewdate'];
		$seo_url 	  = $row['seo_url'];
		$score     	  = $row['score'];
		$description  = $row['description'];
		$reaction 	  = $row['reaction'];

?>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/reviews/" class="btn btn-dark">Terug</a></div>
		<h2>Review bewerken</h2>
	</div>
	
</div>

<div class="row mt-4">
		
	<div class="col-md-12">
		<?php include_once("forms/edit.php"); ?>
	</div>
	
</div>

<?php } ?>