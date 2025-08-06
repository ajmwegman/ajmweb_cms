<?php
/**
 * Duplicate Content
 * 
 * Dupliceert content voor verschillende talen
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
    echo json_encode(['success' => false, 'message' => 'Niet geautoriseerd']);
    exit;
}

// Get parameters
$content_id = $_POST['content_id'] ?? 0;
$target_languages = $_POST['target_languages'] ?? [];
$table = $_POST['table'] ?? 'group_content';

// Validate input
if (empty($content_id) || empty($target_languages) || !is_array($target_languages)) {
    echo json_encode(['success' => false, 'message' => 'Ongeldige parameters']);
    exit;
}

// Whitelist allowed tables for security
$allowed_tables = ['group_content', 'group_menu'];
if (!in_array($table, $allowed_tables)) {
    echo json_encode(['success' => false, 'message' => 'Ongeldige tabel']);
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Haal originele content op
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = ?");
    $stmt->execute([$content_id]);
    $original = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$original) {
        throw new Exception('Origineel content niet gevonden');
    }
    
    $created_count = 0;
    $skipped_count = 0;
    
    foreach ($target_languages as $target_lang) {
        // Valideer taalcode
        if (!preg_match('/^[a-z]{2}$/', $target_lang)) {
            continue;
        }
        
        // Check of vertaling al bestaat
        $checkStmt = $pdo->prepare("SELECT id FROM {$table} WHERE group_id = ? AND location = ? AND lang_code = ?");
        $checkStmt->execute([$original['group_id'], $original['location'], $target_lang]);
        
        if ($checkStmt->rowCount() > 0) {
            $skipped_count++;
            continue;
        }
        
        // Maak kopie voor nieuwe taal
        $new_content = $original;
        unset($new_content['id']); // Remove ID so new record is created
        
        // Update voor nieuwe taal
        $new_content['lang_code'] = $target_lang;
        $new_content['title'] = '[TRANSLATE] ' . $original['title'];
        $new_content['status'] = 'n'; // Set inactive until translated
        
        // Set creation date if field exists
        if (isset($new_content['date_created'])) {
            $new_content['date_created'] = date('Y-m-d H:i:s');
        }
        
        // Insert new record
        $fields = array_keys($new_content);
        $placeholders = ':' . implode(', :', $fields);
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") VALUES ({$placeholders})";
        $insertStmt = $pdo->prepare($sql);
        
        // Bind parameters
        foreach ($new_content as $key => $value) {
            $insertStmt->bindValue(':' . $key, $value);
        }
        
        if ($insertStmt->execute()) {
            $created_count++;
        }
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'created' => $created_count,
        'skipped' => $skipped_count,
        'message' => "Succesvol {$created_count} vertalingen aangemaakt" . 
                    ($skipped_count > 0 ? " ({$skipped_count} overgeslagen omdat ze al bestonden)" : "")
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    
    echo json_encode([
        'success' => false, 
        'message' => 'Database fout: ' . $e->getMessage()
    ]);
}
?>