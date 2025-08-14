<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/users.class.php';
$usersClass = new users($pdo);
$users = $usersClass->getAllUsers();
?>
<h2>Rolbeheer</h2>
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-5"><h5>E-mailadres</h5></div>
          <div class="col-4"><h5>Rol</h5></div>
          <div class="col-3 text-end"><h5>Opslaan</h5></div>
        </div>
      </div>
      <div class="card-body">
        <?php foreach ($users as $user): ?>
        <form class="row mb-2" method="post" action="modules/roles/bin/update.php">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
          <div class="col-5 pt-2"><?php echo htmlspecialchars($user['email']); ?></div>
          <div class="col-4">
            <select name="role" class="form-select form-select-sm">
              <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
              <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
          </div>
          <div class="col-3 text-end">
            <button type="submit" class="btn btn-primary btn-sm">Opslaan</button>
          </div>
        </form>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
