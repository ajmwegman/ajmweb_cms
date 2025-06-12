<?php
class carousel {

    private PDO $pdo; // ✅ Declareer de property

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function getItems(): array {
        $sql = "SELECT * FROM group_carousel WHERE active = 'y' ORDER BY sortnum ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCarouselSettings(int $group_id): array|false {
        $sql = "SELECT * FROM group_carousel_settings WHERE group_id = :group_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ 'group_id' => $group_id ]);
        return $stmt->fetch();
    }
}
