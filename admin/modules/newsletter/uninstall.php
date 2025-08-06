<?php
/**
 * Newsletter Module Uninstall Script
 * 
 * Dit script verwijdert veilig de newsletter module:
 * - Verwijdert database tabellen
 * - Verwijdert bestanden
 * - Maakt backup van data
 */

// Start sessie voor beveiliging
session_start();

// Controleer admin toegang
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die('Toegang geweigerd. Alleen administrators kunnen de module verwijderen.');
}

// Include configuratie
require_once(__DIR__ . '/config.php');

class NewsletterUninstaller {
    private $pdo;
    private $errors = [];
    private $warnings = [];
    private $success = [];
    private $backup_created = false;
    
    public function __construct() {
        $this->connectDatabase();
    }
    
    /**
     * Maak database connectie
     */
    private function connectDatabase() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            $this->success[] = 'Database connectie succesvol.';
        } catch (PDOException $e) {
            $this->errors[] = 'Database connectie mislukt: ' . $e->getMessage();
        }
    }
    
    /**
     * Maak backup van data
     */
    private function createBackup() {
        if (empty($this->pdo)) {
            return false;
        }
        
        $backup_dir = __DIR__ . '/backup_' . date('Y-m-d_H-i-s');
        if (!mkdir($backup_dir, 0755, true)) {
            $this->errors[] = 'Kan backup map niet aanmaken.';
            return false;
        }
        
        $tables = [
            'newsletter_subscribers',
            'newsletter_campaigns', 
            'newsletter_queue',
            'newsletter_stats',
            'newsletter_templates',
            'newsletter_logs'
        ];
        
        foreach ($tables as $table) {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM {$table}");
                $stmt->execute();
                $data = $stmt->fetchAll();
                
                if (!empty($data)) {
                    $backup_file = $backup_dir . '/' . $table . '.json';
                    file_put_contents($backup_file, json_encode($data, JSON_PRETTY_PRINT));
                    $this->success[] = "Backup gemaakt van tabel '{$table}' (" . count($data) . " records).";
                }
            } catch (PDOException $e) {
                $this->warnings[] = "Kon geen backup maken van tabel '{$table}': " . $e->getMessage();
            }
        }
        
        $this->backup_created = true;
        return true;
    }
    
    /**
     * Voer uninstallatie uit
     */
    public function uninstall($keep_data = false) {
        if (!empty($this->errors)) {
            return false;
        }
        
        try {
            // Maak backup als data niet behouden moet blijven
            if (!$keep_data) {
                $this->createBackup();
            }
            
            // Verwijder database tabellen
            $this->dropTables();
            
            // Verwijder bestanden
            $this->removeFiles();
            
            $this->success[] = 'Newsletter module succesvol verwijderd!';
            return true;
            
        } catch (Exception $e) {
            $this->errors[] = 'Uninstallatie mislukt: ' . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Verwijder database tabellen
     */
    private function dropTables() {
        $tables = [
            'newsletter_logs',
            'newsletter_stats', 
            'newsletter_queue',
            'newsletter_templates',
            'newsletter_campaigns',
            'newsletter_subscribers'
        ];
        
        foreach ($tables as $table) {
            try {
                $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                $this->success[] = "Tabel '{$table}' verwijderd.";
            } catch (PDOException $e) {
                $this->warnings[] = "Kon tabel '{$table}' niet verwijderen: " . $e->getMessage();
            }
        }
    }
    
    /**
     * Verwijder bestanden
     */
    private function removeFiles() {
        $files_to_remove = [
            __DIR__ . '/index.php',
            __DIR__ . '/init.php',
            __DIR__ . '/config.php',
            __DIR__ . '/cron.php',
            __DIR__ . '/database.sql',
            __DIR__ . '/README.md',
            __DIR__ . '/install.php',
            __DIR__ . '/uninstall.php'
        ];
        
        $dirs_to_remove = [
            __DIR__ . '/controllers',
            __DIR__ . '/views',
            __DIR__ . '/ajax',
            __DIR__ . '/assets',
            __DIR__ . '/templates',
            __DIR__ . '/lang',
            __DIR__ . '/includes',
            __DIR__ . '/uploads',
            __DIR__ . '/logs'
        ];
        
        // Verwijder bestanden
        foreach ($files_to_remove as $file) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    $this->success[] = "Bestand verwijderd: " . basename($file);
                } else {
                    $this->warnings[] = "Kon bestand niet verwijderen: " . basename($file);
                }
            }
        }
        
        // Verwijder mappen
        foreach ($dirs_to_remove as $dir) {
            if (is_dir($dir)) {
                if ($this->removeDirectory($dir)) {
                    $this->success[] = "Map verwijderd: " . basename($dir);
                } else {
                    $this->warnings[] = "Kon map niet verwijderen: " . basename($dir);
                }
            }
        }
        
        // Probeer de hoofdmap te verwijderen als deze leeg is
        if (is_dir(__DIR__) && count(scandir(__DIR__)) <= 2) { // . en ..
            if (rmdir(__DIR__)) {
                $this->success[] = "Newsletter module map volledig verwijderd.";
            }
        }
    }
    
    /**
     * Verwijder directory recursief
     */
    private function removeDirectory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Toon uninstallatie resultaat
     */
    public function displayResults() {
        echo '<!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Newsletter Module Uninstallatie</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header bg-danger text-white">
                                <h3 class="mb-0">
                                    <i class="bi bi-trash"></i> Newsletter Module Uninstallatie
                                </h3>
                            </div>
                            <div class="card-body">';
        
        if (!empty($this->errors)) {
            echo '<div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle"></i> Kritieke Fouten</h5>
                    <ul class="mb-0">';
            foreach ($this->errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul></div>';
        }
        
        if (!empty($this->warnings)) {
            echo '<div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle"></i> Waarschuwingen</h5>
                    <ul class="mb-0">';
            foreach ($this->warnings as $warning) {
                echo '<li>' . htmlspecialchars($warning) . '</li>';
            }
            echo '</ul></div>';
        }
        
        if (!empty($this->success)) {
            echo '<div class="alert alert-success">
                    <h5><i class="bi bi-check-circle"></i> Succesvol Voltooid</h5>
                    <ul class="mb-0">';
            foreach ($this->success as $success) {
                echo '<li>' . htmlspecialchars($success) . '</li>';
            }
            echo '</ul></div>';
        }
        
        if ($this->backup_created) {
            echo '<div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> Backup Gemaakt</h5>
                    <p>Er is een backup gemaakt van alle newsletter data. Deze bevindt zich in een map met de naam "backup_[datum_tijd]".</p>
                </div>';
        }
        
        if (empty($this->errors)) {
            echo '<div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle"></i> Belangrijk</h5>
                    <ul>
                        <li>Verwijder de cron job die de newsletter module gebruikt</li>
                        <li>Update eventuele links naar de newsletter module</li>
                        <li>Controleer of er geen externe verwijzingen naar de module zijn</li>
                    </ul>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="../" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Terug naar Admin Panel
                    </a>
                </div>';
        } else {
            echo '<div class="d-grid gap-2">
                    <a href="uninstall.php" class="btn btn-danger">
                        <i class="bi bi-arrow-clockwise"></i> Probeer Opnieuw
                    </a>
                    <a href="../" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Terug naar Admin Panel
                    </a>
                </div>';
        }
        
        echo '</div></div></div></div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>';
    }
}

// Voer uninstallatie uit
$uninstaller = new NewsletterUninstaller();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keep_data = isset($_POST['keep_data']) && $_POST['keep_data'] === '1';
    $uninstaller->uninstall($keep_data);
}

$uninstaller->displayResults();
?> 