<?php
class adminUsers {
	
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getAllAdminUsers() {

        $sql = "SELECT * FROM group_login ORDER BY username ASC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}
	
	function getAdminUser( $id ) {
		
		$content = array();

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_login WHERE id = ?";

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