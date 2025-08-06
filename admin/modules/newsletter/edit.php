<?php
// Newsletter Campaign Edit
// Include database connection
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . "/system/database.php");

// Get campaign ID from URL rewriting parameters
$campaign_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($campaign_id <= 0) { 
    echo '<div class="alert alert-danger">Geen geldig campagne ID opgegeven</div>'; 
    echo '<a href="/admin/newsletter/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
}

// Get campaign data
try {
    $sql = "SELECT * FROM newsletter_campaigns WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$campaign_id]);
    $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campaign) {
        echo '<div class="alert alert-danger">Nieuwsbrief niet gevonden.</div>';
        echo '<a href="/admin/newsletter/" class="btn btn-secondary">Terug naar overzicht</a>';
        exit;
    }
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Database fout: ' . $e->getMessage() . '</div>';
    echo '<a href="/admin/newsletter/" class="btn btn-secondary">Terug naar overzicht</a>';
    exit;
}

$id = $campaign['id'];
$subject = $campaign['subject'];
$content = $campaign['content'];
$sender_name = $campaign['sender_name'] ?? '';
$sender_email = $campaign['sender_email'] ?? '';
$scheduled_at = $campaign['scheduled_at'] ?? '';
$status = $campaign['status'] ?? 'draft';
$template_id = $campaign['template_id'] ?? '';
$target_audience = $campaign['target_audience'] ?? '';
?>


<div class="row mt-4" id="add_space">
    <div class="col-md-12">
        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-success" id="readyForSendBtn">
                <i class="bi bi-check-circle"></i> Klaar voor Verzenden
            </button>
            <button type="button" class="btn btn-outline-secondary" id="saveAsDraftBtn">
                <i class="bi bi-save"></i> Opslaan als Concept
            </button>
            <button type="button" class="btn btn-outline-primary" id="previewBtn">
                <i class="bi bi-eye"></i> Voorbeeld
            </button>
            <div class="ms-auto">
                <a href="/admin/newsletter/" class="btn btn-dark">
                    <i class="bi bi-arrow-left"></i> Terug
                </a>
            </div>
        </div>
        <h2>Campagne bewerken</h2>
    </div>
</div>

<div class="row mt-4" id="add_space">
    <div class="col-md-12">
        <?php include_once("forms/edit.php"); ?>
    </div>
</div> 