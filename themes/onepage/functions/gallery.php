<?php
class gallery {

        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }
	
	function getItems() {

		$sql = "SELECT * FROM group_gallery WHERE active = 'y' ORDER BY sortnum ASC";

        $stmt = $this->pdo->prepare( $sql );
		$stmt->execute();
		
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return($row);

	}
    
    function getCategories() {
        
        $sql = "SELECT DISTINCT(category) FROM group_gallery WHERE active = 'y' ORDER BY category ASC";

        $stmt = $this->pdo->prepare( $sql );
		$stmt->execute();
		
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return($row);
    }
}
?>