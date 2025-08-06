<?php
@session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Get the document root path
    $path = $_SERVER['DOCUMENT_ROOT'];
    
    // Check if required files exist before including them
    $requiredFiles = [
        $path . "/system/database.php",
        $path . "/admin/src/database.class.php",
        $path . "/admin/modules/newsletter_memberlist/src/module.class.php",
        $path . "/admin/functions/forms.php"
    ];
    
    foreach ($requiredFiles as $file) {
        if (!file_exists($file)) {
            throw new Exception("Required file not found: " . $file);
        }
    }
    
    // Include required files
    require_once($path . "/system/database.php");
    require_once($path . "/admin/src/database.class.php");
    require_once($path . "/admin/modules/newsletter_memberlist/src/module.class.php");
    require_once($path . "/admin/functions/forms.php");

    // Check if database connection is available
    if (!isset($pdo)) {
        throw new Exception("Database connection not available");
    }

    $db = new database($pdo);
    $memberlist = new newsletter_memberlist($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';
        
        $list = $memberlist->searchMembers($searchTerm);
        
        // Return the filtered list HTML
        ?>
        <div id="menuList" class="list-group">
            <?php
            if (empty($list)) {
                echo '<div class="alert alert-info">Geen resultaten gevonden.</div>';
            } else {
                $row_index = 0;
                foreach ($list as $link) {
                    ?>
                    <div class="row mt-1" style="background-color: #F1F1F1;">
                        <div class="col-3">
                            <h5><?php echo htmlspecialchars($link['firstname']); ?></h5>
                        </div>
                        <div class="col-3">
                            <h5><?php echo htmlspecialchars($link['lastname']); ?></h5>
                        </div>
                        <div class="col-4">
                            <h5><?php echo htmlspecialchars($link['emailaddress']); ?></h5>
                        </div>
                        <div class="col-lg-1 text-center">
                            <div class="form-check-inline form-switch mt-2">
                                <input class="form-check-input switchbox" type="checkbox" data-set="<?php echo htmlspecialchars($link['hash']); ?>" <?php echo ($link['active'] == 'y') ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="col-lg-1 text-end">
                            <button type="button" value="<?php echo $link['id']; ?>"
                                    data-row-index="<?php echo $row_index; ?>"
                                    data-message="Weet je zeker dat je <?php echo htmlspecialchars($link['emailaddress']); ?> wilt verwijderen?"
                                    class="btn btn-danger btn-sm btn-ok mt-1 btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#dialogModal"
                                    data-row-id="<?php echo $link['id']; ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button type="button" value="<?php echo $link['id']; ?>"
                                    class="btn btn-primary btn-sm btn-edit mt-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="<?php echo $link['id']; ?>"
                                    data-firstname="<?php echo htmlspecialchars($link['firstname']); ?>"
                                    data-lastname="<?php echo htmlspecialchars($link['lastname']); ?>"
                                    data-email="<?php echo htmlspecialchars($link['emailaddress']); ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                    $row_index++;
                }
            } ?>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="dialogModal" tabindex="-1" aria-labelledby="dialogModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="dialogModalLabel">Let op!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p class="text-center dialogmessage"></p>
              </div>
              <div class="modal-footer">
                <input type="hidden" class="RowId" value="" id="RowId">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger btn-delete btn-ok" data-bs-dismiss="modal">Verwijderen</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Nieuwsbrieflid bewerken</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="editMemberForm">
                <div class="modal-body">
                  <input type="hidden" name="id" id="edit-id">
                  <div class="mb-3">
                    <label for="edit-firstname" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" id="edit-firstname" name="firstname" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit-lastname" class="form-label">Achternaam</label>
                    <input type="text" class="form-control" id="edit-lastname" name="lastname" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit-email" class="form-label">E-mailadres</label>
                    <input type="email" class="form-control" id="edit-email" name="email" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                  <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
    }
} catch (Exception $e) {
    // Log the error and return a simple error message
    error_log("Search error: " . $e->getMessage());
    echo "<div class='alert alert-danger'>Er is een fout opgetreden bij het zoeken: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?> 