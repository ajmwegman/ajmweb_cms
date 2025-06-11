<?php
class content {
	
/*
id
hash
group_id
title
content
location
seo_url
keywords
sortnum
status
*/
	
	public $id = 0;
	public $hash = 0;
	public $group_id = 0;
	public $title = 0;
	public $content = 0;
	/*public $location = 0;
	public $seo_url = 0;
	public $keywords = 0;
	public $sortnum = 0;
        public $status = 0;
        */
        /** @var PDO */
        private PDO $pdo;
        function __construct($pdo) {
                $this->pdo = $pdo;
    }

	function getKeywords( $groupid, $keyword ) {

		$result = array();
		if(!$groupid|| !$keyword) {
			return "E01";
		} else {

			//$sql = "SELECT * FROM group_keywords WHERE group_id = :groupid AND replacer = :replacer ORDER BY replacer ASC LIMIT 4";
			$sql = "SELECT keyword FROM group_keywords WHERE group_id = :groupid AND keyword LIKE :keyword ORDER BY keyword ASC LIMIT 4";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid, 'keyword' => '%'.$keyword.'%' ] );

			//$stmt->execute( [ 'groupid' => $groupid, 'replacer'=>"%".$keyword ] );

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$result[] = $row['keyword'];
			}
			
			return $result;
			
		}
	}
	
	function getContentItems( $groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_content WHERE group_id = :groupid ORDER BY sortnum ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
			
		}
	}
	function getContent( $id ) {
		
		$content = array();

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_content WHERE id = ?";

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