<?php
// Newsletter Campaign Actions Handler
header('Content-Type: application/json');

// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");

// Check if we have database connection
if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Database connection not available</div>']);
    exit;
}

// Handle different request types
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            // Validate required fields for new newsletter
            if (empty($_POST['subject'])) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Naam van de nieuwsbrief is verplicht</div>']);
                exit;
            }
            
            try {
                $sql = "INSERT INTO newsletter_campaigns (subject, content, status, created_at) 
                        VALUES (?, '', 'draft', NOW())";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    $_POST['subject']
                ]);
                
                if ($result) {
                    $newsletter_id = $pdo->lastInsertId();
                    echo json_encode([
                        'success' => true, 
                        'message' => '<div class="alert alert-success" role="alert">Nieuwsbrief toegevoegd</div>',
                        'newsletter_id' => $newsletter_id
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Fout bij opslaan van nieuwsbrief</div>']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Database fout: ' . $e->getMessage()]);
            }
            break;
            
        case 'edit':
            $campaign_id = $_POST['campaign_id'] ?? 0;
            if (empty($_POST['subject'])) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Onderwerp is verplicht</div>']);
                exit;
            }
            if (empty($_POST['content'])) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Inhoud is verplicht</div>']);
                exit;
            }
            if ($campaign_id <= 0) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Geen geldig nieuwsbrief ID</div>']);
                exit;
            }
            
            try {
                $sql = "UPDATE newsletter_campaigns 
                        SET subject = ?, content = ?, status = ?, updated_at = NOW() 
                        WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    $_POST['subject'],
                    $_POST['content'],
                    $_POST['status'] ?? 'draft',
                    $campaign_id
                ]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => '<div class="alert alert-success" role="alert">Nieuwsbrief bijgewerkt</div>']);
                } else {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Fout bij bijwerken van nieuwsbrief</div>']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Database fout: ' . $e->getMessage()]);
            }
            break;
            
        case 'delete':
            $campaign_id = $_POST['campaign_id'] ?? 0;
            if ($campaign_id <= 0) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Geen geldig nieuwsbrief ID</div>']);
                exit;
            }
            
            try {
                $sql = "DELETE FROM newsletter_campaigns WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$campaign_id]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => '<div class="alert alert-success" role="alert">Nieuwsbrief verwijderd</div>']);
                } else {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Fout bij verwijderen van nieuwsbrief</div>']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Database fout: ' . $e->getMessage() . '</div>']);
            }
            break;
            
        case 'send':
            $campaign_id = $_POST['campaign_id'] ?? 0;
            if ($campaign_id <= 0) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Geen geldig nieuwsbrief ID</div>']);
                exit;
            }
            
            try {
                // Check if campaign exists and is in draft status
                $sql = "SELECT id, subject, status FROM newsletter_campaigns WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$campaign_id]);
                $campaign = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$campaign) {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Nieuwsbrief niet gevonden</div>']);
                    exit;
                }
                
                if ($campaign['status'] !== 'ready') {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Alleen nieuwsbrieven die klaar zijn voor verzending kunnen worden verzonden</div>']);
                    exit;
                }
                
                // Update campaign status to sent
                $sql = "UPDATE newsletter_campaigns SET status = 'sent', sent_at = NOW() WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$campaign_id]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => '<div class="alert alert-success" role="alert">Nieuwsbrief verzonden</div>']);
                } else {
                    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Fout bij verzenden van nieuwsbrief</div>']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Database fout: ' . $e->getMessage() . '</div>']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Onbekende actie</div>']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => '<div class="alert alert-danger" role="alert">Ongeldige aanvraag</div>']);
}
?> 