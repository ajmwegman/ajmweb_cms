<?php
class site {

	public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }
    
    /* START GETTNG DATA FOR WEBSITES */
	function getConfig ($loc_website) {

		if(!$loc_website) {
			return "E01";
		} else {

			$sql = "SELECT * FROM config WHERE loc_website LIKE :loc_website";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'loc_website' => "%".$loc_website."%" ] );

			$row = $stmt->fetch();
			
            if(empty($row)) {
               return "Row is leeg"; 
            } else {
			 return($row);
            }
        }
    }    
    
    function getGroupConfig ($web_naam) {

		if(!$loc_website) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_config WHERE web_naam LIKE :web_naam";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'web_naam' => "%".$web_naam."%" ] );

			$row = $stmt->fetch();
			
            if(empty($row)) {
               return "Row is leeg"; 
            } else {
			 return($row);
            }
        }
    }    
    
    
	function getConfigSite ($shop_id) {
                    
        $sql = 'SELECT * FROM config_site WHERE shop_id = :shop_id';

  		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'shop_id' => $shop_id ] );

		$row = $stmt->fetch();
			
        if(empty($row)) {
           return "Row is leeg"; 
        } else {
		  return($row);
        }
    }
    
	function getGroupId ($web_naam) {
        
        $sql = 'SELECT * FROM group_config WHERE web_naam LIKE :web_naam';

  		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'web_naam' => '%'.$web_naam.'%' ] );

		$row = $stmt->fetch();
			
        if(empty($row)) {
           return "Row is leeg"; 
        } else {
		  return($row);
        }
    }
    
    /* Navbar */
    function dropdownCounter( $location ) {

		if(!$location) {
			return "E01";
		} else {

			$sql = "SELECT COUNT(*) AS total FROM group_content WHERE location = :location AND status = 'y'";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'location' => $location ] );

			$row = $stmt->fetch();
			
			return $row['total'];
			
		}
	}
    
	function getActiveMenuItems( $groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_menu WHERE group_id = :groupid AND status = 'y' ORDER BY sortnum ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
			
		}
	}
    
	function MenuItemsByLocation( $groupid , $location) {

		if(!$groupid || !$location) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_content WHERE group_id = :groupid AND location = :location AND status = 'y' ORDER BY sortnum ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid, 'location' => $location ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
			
		}
	}
	
	function getActiveContent( $groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_content WHERE group_id = :groupid AND status = 'y' ORDER BY sortnum ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);	
		}
	}
    
    function getWebsiteInfo( $groupid ) {

		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_config WHERE group_id = :groupid LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			$row = $stmt->fetch();
			
			return($row);
        }
	}
    
    function getSingleContent( $seo_url ) {

		if(!$seo_url) {
			return "E01";
		} else {

			$sql = "SELECT * FROM group_content WHERE seo_url = :seo_url LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'seo_url' => $seo_url ] );

			$row = $stmt->fetch();
			
			return($row);
        }
	}
    
   function getLatestReview() {

			$sql = "SELECT seo_url FROM group_reviews WHERE active = :active ORDER BY reviewdate DESC LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'active' => 'y' ] );

			$row = $stmt->fetch();
			
			return($row);
	}
    function getCategoryContent($group_id, $location, $limit) {
        
        $sql = "SELECT * FROM group_content WHERE group_id = :group_id AND location = :location ORDER BY sortnum ASC LIMIT ".$limit;

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'group_id' => $group_id, 'location' => $location ] );

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
        return($row);
    }
    
    function getKeywords($groupid ) {

        $keywords = array();
        
		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT keyword FROM group_keywords WHERE group_id = :groupid ORDER BY hash ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			while($row = $stmt->fetch() ) {
                
                $keywords[] = $row['keyword'];
            }
			
            return $keywords;
		}
	}
    
    function getReplacers($groupid ) {

        $replacers = array();
        
		if(!$groupid) {
			return "E01";
		} else {

			$sql = "SELECT replacer FROM group_keywords WHERE group_id = :groupid ORDER BY hash ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'groupid' => $groupid ] );

			while($row = $stmt->fetch() ) {
                
                $replacers[] = $row['replacer'];
            }
			
            return $replacers;
		}
	}
    
    function getArea($sid ) {

        $names = array();
        
		if(!$sid) {
			return "E01";
		} else {

			$sql = "SELECT name FROM area, area_link WHERE area.area_id = area_link.area_id AND area_link.shop_id = :shop_id ORDER BY name ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'shop_id' => $sid ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return($row);
		}
	}
    
    function getBanners() {
            
        $date = date("Y-m-d");
        $active = 'y';
		
        $sql = "SELECT * FROM group_banners WHERE startdate <= :startdate AND enddate >= :enddate AND active = :active";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'startdate' => $date, 'enddate' => $date, 'active' => $active] );

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(empty($row)) {
           return "E10"; 
        } else {
		  return($row);
        }
	}
    
    function getReviews($order="DESC", $limit=10) {

        $order =!isset($order) ? "ASC" : "DESC";
        $sql = "SELECT *, DATE_FORMAT(reviewdate,'%d-%m-%Y') AS date FROM group_reviews WHERE active = :active ORDER BY reviewdate ".$order." LIMIT ".$limit;
        
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'active' => 'y' ] );

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return($row);
	}
    
    function getSingleReview( $seo_url ) {

		if(!$seo_url) {
			return "E01";
		} else {

			$sql = "SELECT *, DATE_FORMAT(reviewdate,'%d-%m-%Y') AS date FROM group_reviews WHERE seo_url = :seo_url LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'seo_url' => $seo_url ] );

			$row = $stmt->fetch();
			
			return($row);
        }
	}
    
    function build_text($text, $keywords, $replacers) {
    
        $new_text = str_replace($keywords, $replacers, $text);
    
        return $new_text;
    }
    
    function build_section($content, $input, $script) {
    
        $build_section = str_replace($input, $script, $content);
    
        return $build_section;
    }
    
    function limit_text($text, $limit) {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }
    
    
   public function getUserByHash($hash)
{
    try {
        // Bereid een SELECT query voor om het gebruikers-ID op te halen op basis van de hash
        $query = 'SELECT id FROM site_users WHERE hash = :hash LIMIT 1';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);

        $stmt->execute();

        // Controleer of er een gebruiker is gevonden
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user; // Geef de gevonden gebruikersgegevens terug
        } else {
            return false; // Geen gebruiker gevonden met die hash
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false; // Geef false terug als er een fout optreedt
    }
}

}
?>