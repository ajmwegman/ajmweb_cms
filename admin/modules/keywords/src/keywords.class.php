<?php
class keywords {

        /** @var PDO */
        private PDO $pdo;
	
        function __construct($pdo) {
                $this->pdo = $pdo;
    }

	function getKeywordsList($groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_keywords WHERE group_id = :groupid ORDER BY keyword ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
			
		}
	}
}
?>