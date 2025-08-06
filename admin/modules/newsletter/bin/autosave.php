<?php
@session_start();

$path = $_SERVER['DOCUMENT_ROOT'];

require_once( $path."/system/database.php" );
require_once( $path."/admin/src/database.class.php" );

$db = new database($pdo);

$success = '<div class="loader spinner-grow" style="width: 1rem; height: 1rem;" role="status">
			<span class="sr-only"></span>
			</div>';

$error = '<div class="alert alert-danger" role="alert">Er is iets fout gegaan!</div>';

if ( isset( $_POST[ 'id' ] ) && isset( $_POST[ 'field' ] ) && isset( $_POST[ 'value' ] ) ) {

	$id    = (int)$_POST[ 'id' ];
	$field = $_POST[ 'field' ];
	$value = $_POST[ 'value' ];
	
	// Debug: Log the received parameters
	error_log("Newsletter autosave received: ID=$id, Field=$field, Value=$value");
	
	// Validate field name to prevent SQL injection
	$allowed_fields = ['subject', 'content', 'sender_name', 'sender_email', 'scheduled_at', 'status', 'target_audience'];
	if (!in_array($field, $allowed_fields)) {
		error_log("Newsletter autosave error: Invalid field '$field'");
		echo $error;
		exit;
	}
	
	try {
		$sql = "UPDATE newsletter_campaigns SET {$field}=:{$field}, updated_at = NOW() WHERE id=:id";
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute([$field=>$value, 'id'=>$id]);
		
		if($result && $stmt->rowCount() > 0) {
			echo $success;
		} else {
			echo $error;
		}
	} catch (Exception $e) {
		error_log("Newsletter autosave error: " . $e->getMessage());
		echo $error;
	}
} else {
  echo $error;
}

?> 