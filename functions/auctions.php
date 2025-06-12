<?php
class Auction {

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProductBySeoTitle(string $seoTitle): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM group_products WHERE active = 'y' AND seoTitle = :seoTitle LIMIT 1");
            $stmt->execute(['seoTitle' => $seoTitle]);
            $auction = $stmt->fetch(PDO::FETCH_ASSOC);
            return $auction ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getAuctions(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM group_auctions WHERE active = 'y' ORDER BY startDate ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getProductData(int $productId): array|false
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM group_products WHERE id = :productId');
            $stmt->execute(['productId' => $productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) return false;

            $stmt = $this->pdo->prepare('SELECT image FROM group_product_images WHERE hash = :hash');
            $stmt->execute(['hash' => $product['hash']]);
            $product['images'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $product;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAuctionData(int $productId): array|false
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM group_auctions WHERE productId = :productId');
            $stmt->execute(['productId' => $productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkChanges(int $id): int
    {
        try {
            $stmt = $this->pdo->prepare('SELECT numbids FROM group_auctions WHERE productId = :productId');
            $stmt->execute(['productId' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['numbids'] : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function registerChanges(int $id): void
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE group_auctions SET numbids = numbids + 1 WHERE productId = :productId');
            $stmt->execute(['productId' => $id]);
        } catch (PDOException $e) {
            // eventueel loggen
        }
    }

    public function getBids(int $lotid, int $limit = 5): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM lotbids WHERE lotid = :lotid ORDER BY timer DESC LIMIT :limit');
            $stmt->bindValue(':lotid', $lotid, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $bids = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bids[] = [
                    'userid' => 'User' . $row['userid'],
                    'bid' => '&euro; ' . number_format($row['bid'], 2, ',', '.'),
                    'timestamp' => $this->formatRelativeTime($row['timer']),
                ];
            }

            return $bids;
        } catch (PDOException $e) {
            return ['error' => 'An error occurred'];
        }
    }

    public function getHighestBid(int $lotid): float
    {
        try {
            $stmt = $this->pdo->prepare('SELECT MAX(bid) AS highest_bid FROM lotbids WHERE lotid = :lotid');
            $stmt->execute(['lotid' => $lotid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($row['highest_bid']) ? (float) $row['highest_bid'] : 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function checkBid(float $bid, int $lotid): bool|string
    {
        try {
            $bid = floor($bid);
            if (!is_numeric($bid)) {
                return 'E01';
            }

            $stmt = $this->pdo->prepare('SELECT bid FROM lotbids WHERE lotid = :lotid ORDER BY bid DESC LIMIT 1');
            $stmt->execute(['lotid' => $lotid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) return true;

            return ($row['bid'] >= $bid) ? 'E02' : true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addBid(float $bid, int $lotid): bool
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO lotbids (lotid, bid, timer) VALUES (:lotid, :bid, NOW())');
            $stmt->execute(['lotid' => $lotid, 'bid' => $bid]);
            $this->registerChanges($lotid);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function formatRelativeTime(string $timestamp): string
    {
        $currentTime = time();
        $timestamp = strtotime($timestamp);
        $timeDifference = $currentTime - $timestamp;

        return match (true) {
            $timeDifference < 60     => "$timeDifference seconden geleden",
            $timeDifference < 3600   => floor($timeDifference / 60) . " minuten geleden",
            $timeDifference < 21600  => floor($timeDifference / 3600) . " uur geleden",
            $timeDifference < 86400  => "vandaag " . date("H:i", $timestamp),
            $timeDifference < 172800 => "gisteren " . date("H:i", $timestamp),
            default                  => date("d-m-Y H:i", $timestamp),
        };
    }
}
?>
