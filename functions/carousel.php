<?php
class carousel {

        /** @var PDO */
        private $pdo;

	public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }
	
	function getItems() {

		$sql = "SELECT * FROM group_carousel WHERE active = 'y' ORDER BY sortnum ASC";

        $stmt = $this->pdo->prepare( $sql );
		$stmt->execute();
		
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return($row);

	}
    
    function getCarouselSettings($group_id) {

		$sql = "SELECT * FROM group_carousel_settings WHERE group_id = :group_id";

        $stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'group_id' => $group_id ] );
		
        $row = $stmt->fetch();
		
		return($row);

	}
}
?>