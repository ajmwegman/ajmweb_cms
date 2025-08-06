<?php
class auction {
	
/*
id
subject
location
seo_url
score
description
reaction
modified
active
*/
	function __construct($pdo) {
		$this->pdo = $pdo;
    }

function getAllAuctions($orderBy = 'enddate', $orderDirection = 'ASC') {
    // Controleer of de $orderBy waarde geldig is
    $validOrderBy = ['enddate', 'startdate', 'productCode'];
    if (!in_array($orderBy, $validOrderBy)) {
        throw new InvalidArgumentException("Ongeldige order kolom: $orderBy");
    }

    // Controleer of de $orderDirection waarde geldig is
    $orderDirection = strtoupper($orderDirection);
    if ($orderDirection !== 'ASC' && $orderDirection !== 'DESC') {
        throw new InvalidArgumentException("Ongeldige order richting: $orderDirection");
    }

    $sql = "
        SELECT 
            group_auctions.*, 
            group_auctions.id AS auctionId,
            group_auctions.hash AS auctionHash, 
            group_auctions.active AS auctionActive, 
            group_auctions.modified AS auctionModified,
            group_products.*, 
            group_products.id AS productId, 
            group_products.hash AS productHash,
            group_products.active AS productActive,
            group_products.modified AS productModified
        FROM group_auctions
        INNER JOIN group_products ON group_auctions.productid = group_products.id
        ORDER BY $orderBy $orderDirection
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows;    
}




function getHighestBidForLot($lotid) {
    $sql = "
        SELECT *
        FROM lotbids
        WHERE lotid = :lotid
        ORDER BY bid DESC
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':lotid', $lotid, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['bid'];
    } else {
        return '0.00';
    }
}


    
    function getActiveProductInfo($query="") {
        
        if(!empty($query)) { 
        
            $sql = "SELECT id, productCode, title FROM group_products WHERE active = 'y' AND title LIKE :title OR productCode LIKE :productCode ORDER BY title ASC LIMIT 5";
            
            $stmt = $this->pdo->prepare( $sql );
            $stmt->execute( ['title' => '%'.$query.'%', 'productCode' => '%'.$query.'%']);

            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        } else {
        
            $sql = "SELECT id, productCode, title FROM group_products WHERE active = 'y' ORDER BY title ASC LIMIT 5";

            $stmt = $this->pdo->prepare( $sql );
            $stmt->execute();

            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
			
		return($row);
	}

    function productIdExists($id)
    {

        $sql = "SELECT COUNT(*) AS count FROM group_auctions WHERE productId = :productId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['productId' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }
    
    function productName($id)
    {

        $sql = "SELECT title FROM group_products WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['title'];
    }
    
    function productCode($id)
    {

        $sql = "SELECT productCode FROM group_products WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['productCode'];
    }
    
	function getAuction( $id ) {
		
		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_auctions WHERE hash = ?";

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