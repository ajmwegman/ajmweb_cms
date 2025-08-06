<?php
class newsletter_memberlist {
	
/*
id
hash
firstname
lastname
emailaddress
regdate
active
*/
	private $pdo;

	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getAllMembers() {

        $sql = "SELECT * FROM group_newslettermembers ORDER BY regdate DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}

	function getMemberById($id) {
		$sql = "SELECT * FROM group_newslettermembers WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	function addMember($firstname, $lastname, $emailaddress) {
		$hash = uniqid();
		$values = array(
			'hash' => $hash,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'emailaddress' => strtolower($emailaddress),
			'regdate' => date('Y-m-d H:i:s'),
			'active' => 'y'
		);
		
		$sql = "INSERT INTO group_newslettermembers (hash, firstname, lastname, emailaddress, regdate, active) 
				VALUES (:hash, :firstname, :lastname, :emailaddress, :regdate, :active)";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($values);
	}

	function updateMember($id, $firstname, $lastname, $emailaddress) {
		$sql = "UPDATE group_newslettermembers SET firstname = :firstname, lastname = :lastname, emailaddress = :emailaddress WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'firstname' => $firstname,
			'lastname' => $lastname,
			'emailaddress' => strtolower($emailaddress),
			'id' => $id
		]);
	}

	function deleteMember($id) {
		$sql = "DELETE FROM group_newslettermembers WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(['id' => $id]);
	}

	function checkDuplicateEmail($email, $excludeId = null) {
		$sql = "SELECT COUNT(*) FROM group_newslettermembers WHERE emailaddress = :email";
		$params = ['email' => strtolower($email)];
		
		if ($excludeId) {
			$sql .= " AND id != :id";
			$params['id'] = $excludeId;
		}
		
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchColumn() > 0;
	}

	function toggleActive($hash, $active) {
		$sql = "UPDATE group_newslettermembers SET active = :active WHERE hash = :hash";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(['active' => $active, 'hash' => $hash]);
	}

	function searchMembers($searchTerm = '') {
		if (empty($searchTerm)) {
			return $this->getAllMembers();
		}
		
		$sql = "SELECT * FROM group_newslettermembers 
				WHERE firstname LIKE :search1 
				OR lastname LIKE :search2 
				OR emailaddress LIKE :search3 
				ORDER BY regdate DESC";
		
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			'search1' => '%' . $searchTerm . '%',
			'search2' => '%' . $searchTerm . '%',
			'search3' => '%' . $searchTerm . '%'
		]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>