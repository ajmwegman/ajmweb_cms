<?php
class site_users {
	
/*
id
hash
firstname
surname
email
regdate
active
*/
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getAllMembers() {

$sql = "SELECT * 
        FROM site_users 
        JOIN site_users_address ON site_users.id = site_users_address.userid 
        ORDER BY site_users.regdate DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}

}
?>