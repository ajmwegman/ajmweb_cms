<?php
$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . "/admin/modules/customers/src/module.class.php");
require_once($path . "/admin/functions/forms.php");

$db = new database($pdo);
$memberlist = new site_users($pdo);

$list = $memberlist->getAllMembers();
?>

<div id="menuList" class="list-group">
    <?php
    $row_index = 0; // Initialiseer de teller voor de rij-index
    foreach ($list as $link) {
        echo input("hidden", $link['hash'], $link['hash']); ?>

        <div class="row mt-1" style="background-color: #F1F1F1;">

            <div class="col-3">
                <h5><?php echo htmlspecialchars($link['firstname']); ?></h5>
            </div>
            <div class="col-3">
                <h5><?php echo htmlspecialchars($link['surname']); ?></h5>
            </div>
            <div class="col-4">
                <h5><?php echo htmlspecialchars($link['email']); ?></h5>
            </div>
            <div class="col-lg-1 text-center">
                <div class="form-check-inline form-switch mt-2">
                    <input class="form-check-input switchbox" type="checkbox" data-set="<?php echo htmlspecialchars($link['hash']); ?>" <?php echo ($link['active'] == 'y') ? 'checked' : ''; ?>>
                </div>
            </div>
            <div class="col-lg-1 text-end">
                <button type="button" value="<?php echo $link['id']; ?>"
                        data-row-index="<?php echo $row_index; ?>"
                        data-message="Weet je zeker dat je <?php echo htmlspecialchars($link['email']); ?> wilt verwijderen?"
                        class="btn btn-danger btn-sm btn-ok mt-1 btn-delete"
                        data-bs-toggle="modal"
                        data-bs-target="#dialogModal"
                        data-row-id="<?php echo $link['id']; ?>">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <?php
        $row_index++; // Verhoog de rij-index voor de volgende iteratie
    } ?>
</div>
