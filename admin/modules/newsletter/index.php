<?php
// Newsletter Module Router
// Admin session check is already handled by CMS in bin/login_check.php

// Check if newsletter tables exist
// Don't require database class again as it's already loaded by CMS

// Check if newsletter_campaigns table exists
$sql = "SHOW TABLES LIKE 'newsletter_campaigns'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$table_exists = $stmt->rowCount() > 0;

if (!$table_exists) {
    // Show install button only
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Newsletter Module Installatie</h5>
                </div>
                <div class="card-body text-center">
                    <p class="mb-4">De newsletter module is nog niet geïnstalleerd. Klik op de onderstaande knop om de installatie te starten.</p>
                    <a href="/admin/modules/newsletter/install.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-download"></i> Newsletter Module Installeren
                    </a>
                    <div class="mt-3">
                        <small class="text-muted">
                            Dit zal de benodigde database tabellen aanmaken en de module configureren.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
}

// Debug informatie
echo "<!-- Debug: Module = {$module}, Action = {$action}, ID = {$id} -->";

// Get page and id parameters from URL rewriting OR admin module parameters
$page = $_GET['page'] ?? '';
$id = $_GET['id'] ?? 0;

// If we're using admin module routing, convert to URL rewriting format
if ($module == 'newsletter' && $action == 'edit' && $id > 0) {
    $page = 'edit';
    $id = $id; // Already set
}

// Router logic - use URL rewriting parameters instead of admin module parameters
switch ($page) {
    case 'edit':
        if ($id > 0) {
            require_once("edit.php");
        } else {
            echo '<div class="alert alert-danger">Geen geldig ID opgegeven voor edit</div>';
            echo '<a href="/admin/newsletter/" class="btn btn-secondary">Terug naar overzicht</a>';
        }
        break;
        
    default:
        ?>
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-0">Nieuwsbrief beheer</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newNewsletterModal">
                    <i class="bi bi-plus"></i> Nieuwe Nieuwsbrief
                </button>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-5">
                                <h5>Onderwerp</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3">
                                <h5>Status</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 d-none d-sm-block">
                                <h5>Taal</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 d-none d-md-block">
                                <h5>Gepland</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 d-none d-lg-block">
                                <h5>Verzonden</h5>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                                <h5>Acties</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="menulist">
                            <?php require("bin/summary.php"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- New Newsletter Modal -->
        <div class="modal fade" id="newNewsletterModal" tabindex="-1" aria-labelledby="newNewsletterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newNewsletterModalLabel">Nieuwe Nieuwsbrief</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newsletterForm" method="post">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Naam van de nieuwsbrief *</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                        <button type="submit" form="newsletterForm" class="btn btn-primary">Opslaan</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="dialogModal" tabindex="-1" aria-labelledby="dialogModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dialogModalLabel">Let op!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center dialogmessage"></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" class="RowId" value="" id="RowId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                        <button type="button" class="btn btn-danger btn-ok">Verwijderen</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
}
?>

<script src="/admin/modules/newsletter/js/menulist.js" type="text/javascript"></script> 