<?php
class orders {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getOrders($offset = 0, $limit = 10) {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOrders() {
        $sql = "SELECT COUNT(*) as cnt FROM orders";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['cnt'];
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function getOrdersForCarrier($carrier) {
        $sql = "SELECT * FROM orders WHERE carrier = :carrier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['carrier' => $carrier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
