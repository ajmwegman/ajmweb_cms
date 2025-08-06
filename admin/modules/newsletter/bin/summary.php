<?php
// Newsletter Campaigns Summary
// Database connection already available from main CMS

$sql = "SELECT * FROM newsletter_campaigns ORDER BY created_at DESC LIMIT 20";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($campaigns)) {
    echo '<div class="text-center py-4">';
    echo '<p class="text-muted mb-3">Nog geen nieuwsbrieven aangemaakt.</p>';
    echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newNewsletterModal">';
    echo '<i class="bi bi-plus"></i> Eerste Nieuwsbrief Aanmaken';
    echo '</button>';
    echo '</div>';
} else {
    foreach ($campaigns as $campaign) {
        ?>
        <div class="row align-items-center py-2 border-bottom" id="row<?php echo $campaign['id']; ?>" data-id="<?php echo $campaign['id']; ?>">
            <div class="col-lg-3 col-md-4 col-sm-5">
                <strong><?php echo htmlspecialchars($campaign['subject']); ?></strong>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3">
                <?php
                // Determine badge color based on status
                $badge_color = 'secondary'; // default
                switch($campaign['status']) {
                    case 'draft':
                        $badge_color = 'secondary';
                        break;
                    case 'ready':
                        $badge_color = 'success';
                        break;
                    case 'scheduled':
                        $badge_color = 'info';
                        break;
                    case 'sending':
                        $badge_color = 'warning';
                        break;
                    case 'sent':
                        $badge_color = 'success';
                        break;
                    case 'cancelled':
                        $badge_color = 'danger';
                        break;
                    case 'archived':
                        $badge_color = 'dark';
                        break;
                }
                
                // Status labels
                $status_labels = [
                    'draft' => 'Concept',
                    'ready' => 'Klaar voor verzending',
                    'scheduled' => 'Gepland',
                    'sending' => 'Verzenden',
                    'sent' => 'Verzonden',
                    'cancelled' => 'Geannuleerd',
                    'archived' => 'Gearchiveerd'
                ];
                $status_label = $status_labels[$campaign['status']] ?? ucfirst($campaign['status']);
                ?>
                <span class="badge bg-<?php echo $badge_color; ?>">
                    <?php echo $status_label; ?>
                </span>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 d-none d-sm-block">
                <span class="badge bg-info">NL</span>
            </div>
            <div class="col-lg-2 col-md-2 d-none d-md-block">
                <small class="text-muted">
                    <?php echo $campaign['scheduled_at'] ? date('d-m-Y H:i', strtotime($campaign['scheduled_at'])) : '-'; ?>
                </small>
            </div>
            <div class="col-lg-2 col-md-2 d-none d-lg-block">
                <small class="text-muted">
                    <?php echo $campaign['sent_at'] ? date('d-m-Y H:i', strtotime($campaign['sent_at'])) : '-'; ?>
                </small>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                <div class="d-flex gap-1 justify-content-end">
                    <a href="/admin/newsletter/edit/<?php echo $campaign['id']; ?>/" 
                       class="btn btn-sm btn-outline-primary" title="Bewerken">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <?php
                    // Determine button based on status
                    $button_html = '';
                    switch($campaign['status']) {
                        case 'draft':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-secondary send-campaign" disabled
                                            data-id="' . $campaign['id'] . '" 
                                            data-name="' . htmlspecialchars($campaign['subject']) . '"
                                            title="Niet klaar voor verzending">
                                            <i class="bi bi-lock"></i>
                                        </button>';
                            break;
                        case 'ready':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-success send-campaign" 
                                            data-id="' . $campaign['id'] . '" 
                                            data-name="' . htmlspecialchars($campaign['subject']) . '"
                                            title="Verzenden">
                                            <i class="bi bi-send"></i>
                                        </button>';
                            break;
                        case 'scheduled':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-info" disabled
                                            title="Gepland voor verzending">
                                            <i class="bi bi-calendar"></i>
                                        </button>';
                            break;
                        case 'sending':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-warning" disabled
                                            title="Wordt verzonden">
                                            <i class="bi bi-hourglass-split"></i>
                                        </button>';
                            break;
                        case 'sent':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-success" disabled
                                            title="Al verzonden">
                                            <i class="bi bi-check-circle"></i>
                                        </button>';
                            break;
                        case 'cancelled':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-danger" disabled
                                            title="Geannuleerd">
                                            <i class="bi bi-x-circle"></i>
                                        </button>';
                            break;
                        case 'archived':
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-dark" disabled
                                            title="Gearchiveerd">
                                            <i class="bi bi-archive"></i>
                                        </button>';
                            break;
                        default:
                            $button_html = '<button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                            title="Niet klaar voor verzending">
                                            <i class="bi bi-lock"></i>
                                        </button>';
                            break;
                    }
                    echo $button_html;
                    ?>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" 
                            data-id="<?php echo $campaign['id']; ?>"
                            data-name="<?php echo htmlspecialchars($campaign['subject']); ?>"
                            title="Verwijderen">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
}
?> 