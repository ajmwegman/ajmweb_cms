<?php
/**
 * Newsletter Module Installation Script
 * 
 * Dit script installeert automatisch de newsletter module:
 * - Maakt database tabellen aan
 * - Controleert systeem vereisten
 * - Configureert standaard instellingen
 * - Maakt benodigde mappen aan
 */

// Start sessie voor beveiliging
session_start();

// Debug: Toon sessie informatie
echo "<h3>Debug: Sessie Informatie</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Controleer admin toegang via loggedin en sid
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'yes' || !isset($_SESSION['sid'])) {
    echo "<h3>Debug: Admin Check Gefaald</h3>";
    echo "loggedin is niet ingesteld of niet gelijk aan 'yes' of sid is niet ingesteld<br>";
    echo "loggedin waarde: " . (isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : 'NIET INGESTELD') . "<br>";
    die('Toegang geweigerd. Alleen administrators kunnen de module installeren.');
}

// Include database configuratie van CMS
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/database.php');

class NewsletterInstaller {
    private $pdo;
    private $errors = [];
    private $warnings = [];
    private $success = [];
    
    public function __construct() {
        $this->checkRequirements();
        $this->connectDatabase();
    }
    
    /**
     * Controleer systeem vereisten
     */
    private function checkRequirements() {
        // PHP versie
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            $this->errors[] = 'PHP 7.4 of hoger is vereist. Huidige versie: ' . PHP_VERSION;
        }
        
        // PHP extensies
        $required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
        foreach ($required_extensions as $ext) {
            if (!extension_loaded($ext)) {
                $this->errors[] = "PHP extensie '{$ext}' is niet geïnstalleerd.";
            }
        }
        
        // Schrijfbare mappen
        $writable_dirs = [
            __DIR__ . '/uploads',
            __DIR__ . '/logs',
            __DIR__ . '/templates'
        ];
        
        foreach ($writable_dirs as $dir) {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    $this->errors[] = "Kan map niet aanmaken: {$dir}";
                }
            } elseif (!is_writable($dir)) {
                $this->warnings[] = "Map is niet schrijfbaar: {$dir}";
            }
        }
        
        // PHPMailer controle
        $phpmailer_path = $_SERVER['DOCUMENT_ROOT'] . '/admin/assets/vendor/PHPMailer/src/PHPMailer.php';
        if (!file_exists($phpmailer_path)) {
            $this->warnings[] = 'PHPMailer niet gevonden. E-mail verzending werkt mogelijk niet.';
        }
    }
    
    /**
     * Maak database connectie
     */
    private function connectDatabase() {
        try {
            // Gebruik de bestaande PDO connectie van het CMS
            global $pdo;
            $this->pdo = $pdo;
            $this->success[] = 'Database connectie succesvol.';
        } catch (PDOException $e) {
            $this->errors[] = 'Database connectie mislukt: ' . $e->getMessage();
        }
    }
    
    /**
     * Voer installatie uit
     */
    public function install() {
        if (!empty($this->errors)) {
            return false;
        }
        
        try {
            // Maak tabellen aan
            $this->createTables();
            
            // Voeg standaard data toe
            $this->insertDefaultData();
            
            // Maak configuratie bestand aan
            $this->createConfigFile();
            
            // Test SMTP connectie
            $this->testSMTPConnection();
            
            $this->success[] = 'Newsletter module succesvol geïnstalleerd!';
            return true;
            
        } catch (Exception $e) {
            $this->errors[] = 'Installatie mislukt: ' . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Maak database tabellen aan
     */
    private function createTables() {
        $tables = [
            'newsletter_subscribers' => "
                CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `email` varchar(255) NOT NULL,
                    `first_name` varchar(100) DEFAULT NULL,
                    `last_name` varchar(100) DEFAULT NULL,
                    `status` enum('pending','active','unsubscribed','bounced') NOT NULL DEFAULT 'pending',
                    `confirmation_token` varchar(64) DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    `confirmed_at` timestamp NULL DEFAULT NULL,
                    `unsubscribed_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `email` (`email`),
                    KEY `status` (`status`),
                    KEY `created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'newsletter_campaigns' => "
                CREATE TABLE IF NOT EXISTS `newsletter_campaigns` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `subject` varchar(255) NOT NULL,
                    `content` longtext NOT NULL,
                    `template_id` int(11) DEFAULT NULL,
                    `status` enum('draft','scheduled','sending','sent','cancelled') NOT NULL DEFAULT 'draft',
                    `scheduled_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    `sent_at` timestamp NULL DEFAULT NULL,
                    `total_sent` int(11) DEFAULT 0,
                    `total_opened` int(11) DEFAULT 0,
                    `total_clicked` int(11) DEFAULT 0,
                    PRIMARY KEY (`id`),
                    KEY `status` (`status`),
                    KEY `scheduled_at` (`scheduled_at`),
                    KEY `created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'newsletter_queue' => "
                CREATE TABLE IF NOT EXISTS `newsletter_queue` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `campaign_id` int(11) NOT NULL,
                    `subscriber_id` int(11) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
                    `scheduled_at` timestamp NULL DEFAULT NULL,
                    `sent_at` timestamp NULL DEFAULT NULL,
                    `failed_at` timestamp NULL DEFAULT NULL,
                    `error_message` text DEFAULT NULL,
                    `opened_at` timestamp NULL DEFAULT NULL,
                    `clicked_at` timestamp NULL DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `campaign_id` (`campaign_id`),
                    KEY `subscriber_id` (`subscriber_id`),
                    KEY `status` (`status`),
                    KEY `scheduled_at` (`scheduled_at`),
                    FOREIGN KEY (`campaign_id`) REFERENCES `newsletter_campaigns` (`id`) ON DELETE CASCADE,
                    FOREIGN KEY (`subscriber_id`) REFERENCES `newsletter_subscribers` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'newsletter_stats' => "
                CREATE TABLE IF NOT EXISTS `newsletter_stats` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `queue_id` int(11) NOT NULL,
                    `type` enum('open','click','bounce','unsubscribe') NOT NULL,
                    `data` text DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `queue_id` (`queue_id`),
                    KEY `type` (`type`),
                    KEY `created_at` (`created_at`),
                    FOREIGN KEY (`queue_id`) REFERENCES `newsletter_queue` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'newsletter_templates' => "
                CREATE TABLE IF NOT EXISTS `newsletter_templates` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `content` longtext NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'newsletter_logs' => "
                CREATE TABLE IF NOT EXISTS `newsletter_logs` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `type` varchar(50) NOT NULL,
                    `message` text NOT NULL,
                    `data` json DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `type` (`type`),
                    KEY `created_at` (`created_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            "
        ];
        
        foreach ($tables as $table_name => $sql) {
            try {
                $this->pdo->exec($sql);
                $this->success[] = "Tabel '{$table_name}' aangemaakt.";
            } catch (PDOException $e) {
                $this->errors[] = "Fout bij aanmaken tabel '{$table_name}': " . $e->getMessage();
            }
        }
        
        // Maak indexes aan
        $indexes = [
            'CREATE INDEX idx_subscribers_email ON newsletter_subscribers(email)',
            'CREATE INDEX idx_subscribers_status ON newsletter_subscribers(status)',
            'CREATE INDEX idx_campaigns_status ON newsletter_campaigns(status)',
            'CREATE INDEX idx_queue_status ON newsletter_queue(status)',
            'CREATE INDEX idx_queue_scheduled ON newsletter_queue(scheduled_at)',
            'CREATE INDEX idx_stats_type ON newsletter_stats(type)',
            'CREATE INDEX idx_logs_type ON newsletter_logs(type)'
        ];
        
        foreach ($indexes as $index_sql) {
            try {
                $this->pdo->exec($index_sql);
            } catch (PDOException $e) {
                // Index bestaat mogelijk al, geen probleem
            }
        }
    }
    
    /**
     * Voeg standaard data toe
     */
    private function insertDefaultData() {
        // Voeg standaard template toe
        $default_template = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset=\"utf-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>{subject}</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
                    .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"header\">
                        <h1>Newsletter</h1>
                    </div>
                    <div class=\"content\">
                        {content}
                    </div>
                    <div class=\"footer\">
                        <p>© 2024 Your Company. All rights reserved.</p>
                        <p>You received this email because you subscribed to our newsletter.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO newsletter_templates (name, content) 
                VALUES ('Default Template', :content)
                ON DUPLICATE KEY UPDATE content = VALUES(content)
            ");
            $stmt->execute(['content' => $default_template]);
            $this->success[] = 'Standaard template toegevoegd.';
        } catch (PDOException $e) {
            $this->warnings[] = 'Kon standaard template niet toevoegen: ' . $e->getMessage();
        }
    }
    
    /**
     * Maak configuratie bestand aan
     */
    private function createConfigFile() {
        // Database configuratie van CMS
        $db_user = 'veilinghuisoranje_db';
        $db_password = 'ZnMx2JqarWPWhsMayXvw';
        $host = 'localhost';
        $db_name = 'veilinghuisoranje_db';
        
        $config_template = "<?php
// Newsletter Module Configuration
// Automatisch gegenereerd tijdens installatie

// Database configuration
define('DB_HOST', '{$host}');
define('DB_NAME', '{$db_name}');
define('DB_USER', '{$db_user}');
define('DB_PASS', '{$db_password}');

// SMTP Configuration (pas aan naar jouw instellingen)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_SECURE', 'tls');
define('SMTP_FROM_EMAIL', 'noreply@yourdomain.com');
define('SMTP_FROM_NAME', 'Your Company Name');

// Newsletter Settings
define('NEWSLETTER_MAX_SEND_PER_HOUR', 1000);
define('NEWSLETTER_BATCH_SIZE', 50);
define('NEWSLETTER_DELAY_BETWEEN_BATCHES', 5);
define('NEWSLETTER_DOUBLE_OPT_IN', true);
define('NEWSLETTER_CONFIRMATION_EXPIRY', 24);

// File paths
define('NEWSLETTER_TEMPLATE_PATH', __DIR__ . '/templates/');
define('NEWSLETTER_ASSETS_PATH', __DIR__ . '/assets/');
define('NEWSLETTER_UPLOADS_PATH', __DIR__ . '/uploads/');

// URL paths
define('NEWSLETTER_BASE_URL', '/admin/modules/newsletter/');
define('NEWSLETTER_SUBSCRIBE_URL', '/newsletter/subscribe.php');
define('NEWSLETTER_UNSUBSCRIBE_URL', '/newsletter/unsubscribe.php');
define('NEWSLETTER_CONFIRM_URL', '/newsletter/confirm.php');

// Security
define('NEWSLETTER_HASH_SALT', '" . bin2hex(random_bytes(32)) . "');
define('NEWSLETTER_TOKEN_EXPIRY', 3600);

// Tracking settings
define('NEWSLETTER_TRACK_OPENS', true);
define('NEWSLETTER_TRACK_CLICKS', true);
define('NEWSLETTER_TRACK_BOUNCES', true);

// Default settings
define('NEWSLETTER_DEFAULT_TEMPLATE', 'default.html');
define('NEWSLETTER_DEFAULT_SUBJECT', 'Nieuwsbrief van {company_name}');
define('NEWSLETTER_DEFAULT_FROM_NAME', 'Newsletter');

// Error reporting
define('NEWSLETTER_DEBUG', true);
define('NEWSLETTER_LOG_FILE', __DIR__ . '/logs/newsletter.log');

// Create directories if they don't exist
if (!file_exists(dirname(NEWSLETTER_LOG_FILE))) {
    mkdir(dirname(NEWSLETTER_LOG_FILE), 0755, true);
}

if (!file_exists(NEWSLETTER_UPLOADS_PATH)) {
    mkdir(NEWSLETTER_UPLOADS_PATH, 0755, true);
}

if (!file_exists(NEWSLETTER_TEMPLATE_PATH)) {
    mkdir(NEWSLETTER_TEMPLATE_PATH, 0755, true);
}
?>";
        
        $config_file = __DIR__ . '/config.php';
        if (file_put_contents($config_file, $config_template)) {
            $this->success[] = 'Configuratie bestand aangemaakt.';
        } else {
            $this->warnings[] = 'Kon configuratie bestand niet aanmaken.';
        }
    }
    
    /**
     * Test SMTP connectie
     */
    private function testSMTPConnection() {
        $phpmailer_path = $_SERVER['DOCUMENT_ROOT'] . '/admin/assets/vendor/PHPMailer/src/PHPMailer.php';
        if (!file_exists($phpmailer_path)) {
            $this->warnings[] = 'PHPMailer niet gevonden. SMTP test overgeslagen.';
            return;
        }
        
        try {
            require_once($phpmailer_path);
            require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/assets/vendor/PHPMailer/src/SMTP.php');
            require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/assets/vendor/PHPMailer/src/Exception.php');
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Standaard SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; // Standaard username
            $mail->Password = 'your-app-password'; // Standaard password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Timeout = 10;
            
            $mail->smtpConnect();
            $this->success[] = 'SMTP connectie succesvol getest.';
            
        } catch (Exception $e) {
            $this->warnings[] = 'SMTP connectie test mislukt: ' . $e->getMessage();
        }
    }
    
    /**
     * Toon installatie resultaat
     */
    public function displayResults() {
        echo '<!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Newsletter Module Installatie</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">
                                    <i class="bi bi-envelope"></i> Newsletter Module Installatie
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
        
        if (empty($this->errors)) {
            echo '<div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> Volgende Stappen</h5>
                    <ol>
                        <li>Configureer je SMTP instellingen in <code>config.php</code></li>
                        <li>Voeg een cron job toe: <code>*/5 * * * * php ' . __DIR__ . '/cron.php</code></li>
                        <li>Test de module via het admin panel</li>
                        <li>Verwijder dit installatie bestand voor beveiliging</li>
                    </ol>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-arrow-right"></i> Ga naar Newsletter Dashboard
                    </a>
                    <a href="../" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Terug naar Admin Panel
                    </a>
                </div>';
        } else {
            echo '<div class="d-grid gap-2">
                    <a href="install.php" class="btn btn-primary">
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

// Installatie uitvoeren
$installer = new NewsletterInstaller();
$installer->install();
$installer->displayResults();
?> 