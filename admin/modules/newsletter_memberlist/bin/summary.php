<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/admin/modules/newsletter_memberlist/src/module.class.php");
require_once($path . "/admin/functions/forms.php");

$db = new database($pdo);
$memberlist = new newsletter_memberlist($pdo);

$list = $memberlist->getAllMembers();
?>

<div id="menuList" class="list-group">
    <?php
    $row_index = 0; // Initialiseer de teller voor de rij-index
    foreach ($list as $link) {
        echo input("hidden", $link['hash'], $link['hash']); ?>

        <div class="row mt-1" style="background-color: #F1F1F1;">

            <div class="col-3">
                <h5><?php echo $link['firstname']; ?></h5>
            </div>
            <div class="col-3">
                <h5><?php echo $link['lastname']; ?></h5>
            </div>
            <div class="col-4">
                <h5><?php echo $link['emailaddress']; ?></h5>
            </div>
            <div class="col-lg-1 text-center">
                <div class="form-check-inline form-switch mt-2">
                    <input class="form-check-input switchbox" type="checkbox" data-set="<?php echo $link['hash']; ?>" <?php echo ($link['active'] == 'y') ? 'checked' : ''; ?>>
                </div>
            </div>
            <div class="col-lg-1 text-end">
                <button type="button" value="<?php echo $link['id']; ?>"
                        data-row-index="<?php echo $row_index; ?>"
                        data-message="Weet je zeker dat je <?php echo $link['emailaddress']; ?> wilt verwijderen?"
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
        $row_index++; // Verhoog de rij-index voor de volgende iteratie
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