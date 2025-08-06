<?php
class banner {
	
/*
id
subject
location
description
startdate
enddate
modified
active
*/
	private $pdo;
	
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getAllBanners() {

        $sql = "SELECT * FROM group_banners ORDER BY startdate DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}
	
	function getBanner( $field, $id ) {
		
		$content = array();

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_banners WHERE {$field} = ?";

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