<?php
class review {
	
/*
id
subject
location
seo_url
score
description
reaction
reviewdate
modified
active
*/
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getAllReviews() {

        $sql = "SELECT * FROM group_reviews ORDER BY reviewdate DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}
	
	function getReview( $id ) {
		
		$content = array();

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_reviews WHERE id = ?";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute([$id]);

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if(!empty($row)) {
		    return($row);
				
			} else {
				return false;
			}
		}
	}
}
?>