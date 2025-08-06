<?php
/*
function active_session( $time = 600 ) {

  // set timeout period in seconds
  $inactive = $time;

  // check to see if $_SESSION['timeout'] is set
  if ( isset( $_SESSION[ 'timeout' ] ) ) {
    $session_life = time() - $_SESSION[ 'timeout' ];
    if ( $session_life > $inactive ) {
      session_destroy();
      return "E01";
    }
  } else {
    $_SESSION[ 'timeout' ] = time();
    return "ok";
  }
}
*/
class menu {
	
	private $pdo;
	
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

	function getGroups() {

		$sites = array();

		$sql = "SELECT group_name FROM site_groups";
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		while ( $row = $stmt->fetch() ) {
		$sites[] = $row[ 'group_name' ];
		}
		return $sites;
	}

	function getGroupId( $groupname ) {

		$sql = "SELECT group_id FROM site_groups WHERE group_name = :groupname";

		// select a particular user by id
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'groupname' => $groupname ] );

		$row = $stmt->fetch();

		$id = $row[ 'group_id' ];

		if ( !$id ) {
		return "error E01";
		} else {
		return $id;
		}
	}
    		
	function getMenuItems( $groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_menu WHERE group_id = :groupid ORDER BY sortnum ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
			
		}
	}

	function getGroupName( $groupid ) {

		$sql = "SELECT group_name FROM site_groups WHERE group_id = :groupid ORDER BY group_name ASC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'groupid' => $groupid ] );

		$row = $stmt->fetch();

		$name = $row[ 'group_name' ];

		if ( !$groupid ) {
		return "error E01";
		} else {
		return $name;
		}
	}	
	
	function getMenuLocations( $groupid ) {

        $result = array();
		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT location FROM group_menu WHERE group_id = :groupid ORDER BY title ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				
					$result[] = $row['location'];
			}
			
            if(!empty($result)) {
                return $result;
            } else {
                return 'No Locations';
            }
			
			
		}
	}
	
	function getMenuNames( $groupid ) {

		$result = array();
		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT title FROM group_menu WHERE group_id = :groupid ORDER BY title ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				
					$result[] = $row['title'];
			}
			
			return $result;
			
		}
	}
	
	function getConfig( $group_id ) {
		
		if(!$group_id) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_config WHERE group_id = :groupid";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $group_id ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);			
		}
	}
    
    function getSiteId() {

		$sql = "SELECT id, web_naam FROM config ORDER BY web_naam";

		// select a particular user by id
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
        return($row);
	}
    
    function getSiteName($id) {

		$sql = "SELECT web_naam FROM config WHERE id = :id";

		// select a particular user by id
		$stmt = $this->pdo->prepare( $sql );
        $stmt->execute( [ "id" => $id ] );

		$row = $stmt->fetch();

		$name = $row[ 'web_naam' ];
        
        return $name;

	}
    
	function getSiteConfig( $id ) {
		
		if(!$id) {
			return "E01";
		} else {

			$sql = "SELECT * FROM 
                config
                INNER JOIN config_site
                ON config.id = config_site.shop_id
                WHERE config.id = :id";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ "id" => $id ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);			
		}
	}
    
    function getSiteLanguages() {
		
            $sql = "SELECT * FROM languages";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute();

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);			
	}
    
    function getLinkedLanguages($site_id, $locale) {
		
            $sql = "SELECT locale FROM language_link WHERE site_id = :site_id AND locale = :locale";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( ["site_id" => $site_id, "locale" => $locale] );

            $row = $stmt->fetch();

            $locale = !empty($row[ 'locale' ]) ? $row[ 'locale' ] : "";

            return $locale;			
        }
}
?>