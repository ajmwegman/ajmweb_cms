<?php
// blog.class.php - Databasebewerkingen voor blogposts en categorieën

class Blog {
    private $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'user', 'password', 'database');
    }

    public function getAllPosts() {
        return $this->db->query("SELECT * FROM blog_posts ORDER BY publication_date DESC")->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostById($id) {
        $stmt = $this->db->prepare("SELECT * FROM blog_posts WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function savePost($title, $content, $status, $publication_date) {
        $stmt = $this->db->prepare("INSERT INTO blog_posts (title, content, status, publication_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $content, $status, $publication_date);
        $stmt->execute();
    }

    public function updatePost($id, $title, $content, $status, $publication_date) {
        $stmt = $this->db->prepare("UPDATE blog_posts SET title = ?, content = ?, status = ?, publication_date = ? WHERE post_id = ?");
        $stmt->bind_param("ssssi", $title, $content, $status, $publication_date, $id);
        $stmt->execute();
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("DELETE FROM blog_posts WHERE post_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function autosave($id, $field, $value) {
        $stmt = $this->db->prepare("UPDATE blog_posts SET $field = ? WHERE post_id = ?");
        $stmt->bind_param("si", $value, $id);
        $stmt->execute();
    }
}
?>
