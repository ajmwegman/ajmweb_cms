<?php
class themeConfig {

  /** @var PDO */
  private PDO $pdo;
    
  function __construct( $pdo ) {
    $this->pdo = $pdo;
  }
    
    public function uploadFile($file)
    {
        // File upload logic here
        // ...

        // Insert file data into database
        $stmt = $this->pdo->prepare("INSERT INTO files (filename, filepath) VALUES (?, ?)");
        $stmt->execute([$filename, $filepath]);

        return "File uploaded successfully";
    }

    public function getLastUploadedFile()
    {
        // Get the last uploaded image from the database
        $stmt = $this->pdo->query("SELECT * FROM files ORDER BY id DESC LIMIT 1");
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file;
    }

    public function deleteFile($id)
    {
        // Get file data from database
        $stmt = $this->pdo->prepare("SELECT * FROM files WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$file) {
            return "File not found";
        }

        // Delete file from file system
        unlink($file['filepath']);

        // Delete file data from database
        $stmt = $this->pdo->prepare("DELETE FROM files WHERE id = ?");
        $stmt->execute([$id]);

        return "File deleted successfully";
    }
    
    public function getDefaultImage()
    {
        // Get the last uploaded image from the database
        $file = $this->getLastUploadedFile();
        
        if (!$file) {
            return "";
        }
        
        return $file['filepath'];
    }
}
?>