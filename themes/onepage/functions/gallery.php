<?php
class gallery {

        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

    public function getItems(): array
    {
        $sql = "SELECT * FROM group_gallery WHERE active = 'y' ORDER BY sortnum ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories(): array
    {
        $sql = "SELECT DISTINCT(category) FROM group_gallery WHERE active = 'y' ORDER BY category ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
