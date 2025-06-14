<?php
class Auction {

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProductBySeoTitle($seoTitle)
    {
        try {            
            $sql = "SELECT * FROM group_products  WHERE group_products.active = 'y' AND group_products.seoTitle = :seoTitle 
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':seoTitle', $seoTitle, PDO::PARAM_STR);
            $stmt->execute();

            $auction = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($auction !== false) {
                return $auction;
            } else {
                echo "Query is empty.". $sql; // Display the message
                return null;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null; // Return null if there's an error
        }
    }


    public function getAuctions()
    {
        try {
            $sql = "SELECT * FROM group_auctions WHERE active = 'y' ORDER BY startDate ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $auctions;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if there's an error to avoid potential issues in the calling code
        }
    }

    public function getProductData($productId)
    {
        try {
            $query = 'SELECT * FROM group_products WHERE id = :productId';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // Fetch the associated images for the product from 'group_product_images' using the 'hash'
            $query_images = 'SELECT image FROM group_product_images WHERE hash = :hash';
            $stmt_images = $this->pdo->prepare($query_images);
            $stmt_images->bindParam(':hash', $product['hash']);
            $stmt_images->execute();
            $images = $stmt_images->fetchAll(PDO::FETCH_COLUMN);

            $product['images'] = $images; // Add the 'images' key to the $product array

            return $product;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false if there's an error to avoid potential issues in the calling code
        }
    }
    
    public function getUniqueCategories()
{
        try {
            $query = 'SELECT DISTINCT category FROM group_products';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $categories;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function getAuctionData($productId)
    {
        try {
            $query = 'SELECT * FROM group_auctions WHERE productId = :productId';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $auction = $stmt->fetch(PDO::FETCH_ASSOC);

            return $auction;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false if there's an error to avoid potential issues in the calling code
        }
    }

    public function checkChanges($id)
    {
        try {
            $query = 'SELECT numbids FROM group_auctions WHERE productId = :productId';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':productId', $productId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (intval($row['numbids']) !== 0) ? $row['numbids'] : 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if there's an error to avoid potential issues in the calling code
        }
    }

    public function registerChanges($id)
    {
        try {
            $query = 'UPDATE group_auctions SET numbids = numbids + 1 WHERE productId = :productId';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':productId', $productId);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getBids($lotid, $limit = 5)
    {
        try {
            $query = 'SELECT * FROM lotbids WHERE lotid = :lotid ORDER BY timer DESC LIMIT :limit';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':lotid', $lotid);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $bidHistory = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bidHistory[] = array(
                    'userid' => 'User'.$row['userid'],
                    'bid' => '&euro; ' . $row['bid'],
                    'timestamp' => $this->formatRelativeTime($row['timer']) 
                );
            }

            return $bidHistory;
        } catch (PDOException $e) {
            // Handle the error, you might want to log it
            return array('error' => 'An error occurred');
        }
    }
    
    public function getHighestBid($lotid)
    {
        try {
            $query = 'SELECT MAX(bid) AS highest_bid FROM lotbids WHERE lotid = :lotid';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':lotid', $lotid);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && isset($row['highest_bid'])) {
            return htmlspecialchars($row['highest_bid']);
            } else {
                return 0;
            }
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 'lalala'; // Return an empty string if there's an error to avoid potential issues in the calling code
        }
    }
    
    public function checkBid($bid, $lotid)
    {
        try {
            $bid = floor($bid);
            if (!is_numeric($bid)) {
                return 'E01'; // Bid refused - not a number
            } else {
                $query = 'SELECT bid FROM lotbids WHERE lotid = :lotid ORDER BY bid DESC LIMIT 1';
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':lotid', $lotid);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row === false) {
                    // No bids found for this lot, so any bid is valid
                    return true;
                }

                $currentBid = $row['bid'];
                if ($currentBid >= $bid) {
                    return 'E02'; // Bid refused - higher bid already exists
                } else {
                    return true;
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false if there's an error to avoid potential issues in the calling code
        }
    }

    public function addBid($bid, $lotid, $userid)
    {
        try {
            // Voeg de bodinformatie toe aan de lotbids-tabel
            $query = 'INSERT INTO lotbids (lotid, userid, bid, timer) VALUES (:lotid, :userid, :bid, NOW())';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':lotid', $lotid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':bid', $bid);
            $result = $stmt->execute();

            if ($result === true) {
                // Als het bod succesvol is toegevoegd, verhoog het aantal biedingen in de group_auctions-tabel
                $updateQuery = 'UPDATE group_auctions SET numbids = numbids + 1 WHERE productId = :lotid';
                $updateStmt = $this->pdo->prepare($updateQuery);
                $updateStmt->bindParam(':lotid', $lotid);
                $updateResult = $updateStmt->execute();

                if ($updateResult === false) {
                    // Als er een fout optreedt bij het bijwerken van numbids, geef een foutmelding
                    echo "Fout bij het bijwerken van numbids.";
                } else {
                    // Als alles goed gaat, registreer de wijzigingen
                    $this->registerChanges($lotid);
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false if there's an error to avoid potential issues in the calling code
        }
    }
    
    public function isFav($userId, $productId)
    {
        try {
            $query = 'SELECT COUNT(*) FROM group_products_favorites WHERE user_id = :userid AND product_id = :productid';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':userid', $userId);
            $stmt->bindParam(':productid', $productId);

            $stmt->execute();

            // Als het aantal groter is dan 0, dan is het product al een favoriet
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function addFav($userId, $productId)
    {
        try {
            // Controleer eerst of de favoriet al bestaat
            $checkQuery = 'SELECT * FROM group_products_favorites WHERE user_id = :userid AND product_id = :productid';
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->bindParam(':userid', $userId);
            $checkStmt->bindParam(':productid', $productId);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Als de favoriet al bestaat, verwijder het
                $deleteQuery = 'DELETE FROM group_products_favorites WHERE user_id = :userid AND product_id = :productid';
                $deleteStmt = $this->pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':userid', $userId);
                $deleteStmt->bindParam(':productid', $productId);
                $deleteResult = $deleteStmt->execute();

                return $deleteResult ? 'removed' : false;
            } else {
                // Voeg anders een nieuwe favoriet toe
                $insertQuery = 'INSERT INTO group_products_favorites (user_id, product_id, created_at) VALUES (:userid, :productid, NOW())';
                $insertStmt = $this->pdo->prepare($insertQuery);
                $insertStmt->bindParam(':userid', $userId);
                $insertStmt->bindParam(':productid', $productId);
                $insertResult = $insertStmt->execute();

                return $insertResult ? 'added' : false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    
    public function formatRelativeTime($timestamp) {
        $currentTime = time();
        $timestamp = strtotime($timestamp);
        $timeDifference = $currentTime - $timestamp;

        if ($timeDifference < 60) {
            return $timeDifference . " seconden geleden";
        } elseif ($timeDifference < 3600) {
            $minutes = floor($timeDifference / 60);
            return $minutes . " minuten geleden";
        } elseif ($timeDifference < 21600) {
            $hours = floor($timeDifference / 3600);
            return $hours . " uur geleden";
        } elseif ($timeDifference < 86400) {
            $hours = floor($timeDifference / 3600);
            return "vandaag " . date("H:i", $timestamp);
        } elseif ($timeDifference < 172800) {
            return "gisteren " . date("H:i", $timestamp);
        } else {
            return date("d-m-Y H:i", $timestamp);
        }
    }
    
    public function checkFinishedAuctions()
    {
        try {
            // Haal alle veilingen op die zijn beëindigd
            $query = 'SELECT * FROM group_auctions WHERE CONCAT(endDate, " ", endTime) < NOW()';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $finishedAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verplaats de gegevens van beëindigde veilingen naar de nieuwe tabel
            foreach ($finishedAuctions as $auction) {
                $lotid = $auction['productId'];

                // Haal het hoogste bod op voor dit lot
                $query = 'SELECT * FROM lotbids WHERE lotid = :lotid ORDER BY bid DESC LIMIT 1';
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':lotid', $lotid);
                $stmt->execute();
                $highestBidRow = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($highestBidRow) {
                    $userid = $highestBidRow['userid'];
                    $highestBid = $highestBidRow['bid'];
                } else {
                    // Geen biedingen gevonden voor dit lot
                    // Doe hier iets, bijvoorbeeld standaardwaarden instellen of de veiling negeren
                    continue;
                }

                // Voeg de gegevens toe aan de nieuwe tabel
                $query = 'INSERT INTO group_auctions_finished (lotid, userid, highestBid, commission, shippingCost) VALUES (:lotid, :userid, :highestBid, :commission, :shippingCost)';
                
                $commission = !empty($auction['commission']) ? $auction['commission'] : 0;
                                
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':lotid', $lotid);
                $stmt->bindParam(':userid', $userid);
                $stmt->bindParam(':highestBid', $highestBid);
                // Voeg de commissie en verzendkosten toe uit de group_auctions tabel
                $stmt->bindParam(':commission', $commission);
                $stmt->bindParam(':shippingCost', $auction['shippingcost']);
                $stmt->execute();
            }

            return true; // Return true om aan te geven dat het proces succesvol is voltooid
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false als er een fout optreedt
        }
    }
}
?>