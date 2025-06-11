<?php
require_once("src/admin_users.class.php"); 

if(!isset($_GET['id'])) { echo "oeps"; } else { $id = $_GET['id']; }

$users = new adminUsers($pdo);	

$result = $users->getAdminUser( $id );

  	foreach ( $result as $data => $row ) {	
		
		$id 		  = $row['id'];
		$hash 		  = $row['hash'];
		$firstname	  = $row['firstname'];
		$surname      = $row['surname'];
		$username	  = $row['username'];
		$status 	  = $row['status'];
		$user_level	  = $row['user_level'];
		$email        = $row['email'];

?>

<div class="row mt-4" id="add_space">
		
	<div class="col-md-12">
		<div class="float-end"><a href="/admin/admin_users/" class="btn btn-dark">Terug</a></div>
		<h2>Gebruiker bewerken</h2>
	</div>
	
</div>

<div class="row mt-4">
		
	<div class="col-md-12">
		<?php include_once("forms/edit.php"); ?>
	</div>
	
</div>

<?php } ?>