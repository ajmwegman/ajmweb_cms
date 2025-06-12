<?php
function get_land(PDO $pdo, int $id): string {
    try {
        $stmt = $pdo->prepare("SELECT name FROM catalog_shippingcost WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['name'] ?? '';
    } catch (PDOException $e) {
        // Foutafhandeling, log eventueel
        return '';
    }
}
?>
