<?php
/**
 * Get Translations
 * 
 * Haalt bestaande vertalingen op voor een content item
 */

session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

// Include required files
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");
require_once($path . "/admin/src/database.class.php");

$db = new database($pdo);

// Check if user is logged in (basic check)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo '<div class="alert alert-danger">Niet geautoriseerd</div>';
    exit;
}

// Get parameters
$content_id = $_POST['content_id'] ?? 0;
$location = $_POST['location'] ?? '';
$group_id = $_POST['group_id'] ?? 1;
$current_lang = $_POST['current_lang'] ?? 'nl';
$table = $_POST['table'] ?? 'group_content';

if (empty($content_id) || empty($location)) {
    echo '<small class="text-muted">Geen content ID of locatie opgegeven</small>';
    exit;
}

try {
    // Haal alle vertalingen op voor deze locatie/group
    $sql = "SELECT id, title, lang_code, status, DATE_FORMAT(date_created, '%d-%m-%Y') as created_date 
            FROM {$table} 
            WHERE group_id = :group_id 
            AND location = :location 
            AND lang_code != :current_lang
            ORDER BY lang_code ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'group_id' => $group_id,
        'location' => $location,
        'current_lang' => $current_lang
    ]);
    
    $translations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($translations)) {
        echo '<small class="text-muted">Geen vertalingen gevonden</small>';
        exit;
    }
    
    // Haal taal labels op
    $langStmt = $pdo->prepare("SELECT locale, label FROM languages");
    $langStmt->execute();
    $languageLabels = [];
    while ($lang = $langStmt->fetch()) {
        $languageLabels[$lang['locale']] = $lang['label'];
    }
    
    // Toon vertalingen
    foreach ($translations as $translation) {
        $statusBadge = $translation['status'] === 'y' ? 
            '<span class="badge badge-success">Actief</span>' : 
            '<span class="badge badge-warning">Inactief</span>';
            
        $langLabel = $languageLabels[$translation['lang_code']] ?? strtoupper($translation['lang_code']);
        
        $editUrl = "edit.php?id=" . $translation['id'];
        
        echo '<a href="' . htmlspecialchars($editUrl) . '" class="translation-link" target="_blank">';
        echo '<strong>' . htmlspecialchars($langLabel) . '</strong><br>';
        echo '<small>' . htmlspecialchars($translation['title']) . '</small>';
        echo $statusBadge;
        echo '<br><small class="text-muted">Aangemaakt: ' . $translation['created_date'] . '</small>';
        echo '</a>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Fout bij ophalen vertalingen: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>