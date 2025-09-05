<?php
require_once("template/config.php");
require_once("bin/login_check.php");

// Deactivate a module if requested
if (isset($_GET['deactivate'])) {
    $deactivateId = (int) $_GET['deactivate'];
    $stmt = $pdo->prepare("UPDATE site_modules SET active = 0 WHERE id = :id");
    $stmt->execute([':id' => $deactivateId]);
    header("Location: admin_modules_overview.php");
    exit;
}

require_once("template/head.php");
require_once("template/navbar.php");

$filterEmail = isset($_GET['email']) ? trim($_GET['email']) : '';

// Build SQL query with optional filter
$sql = "SELECT 
            u.email AS user_email,
            s.name AS site_name,
            m.name AS module_name,
            sm.price_per_month,
            sm.start_date,
            sm.end_date,
            sm.active,
            sm.auto_renew,
            sm.id AS site_module_id
        FROM users u
        JOIN sites s ON s.user_id = u.id
        JOIN site_modules sm ON sm.site_id = s.id
        JOIN modules m ON m.id = sm.module_id";

$params = [];
if ($filterEmail !== '') {
    $sql .= " WHERE u.email LIKE :email";
    $params[':email'] = '%' . $filterEmail . '%';
}

$sql .= " ORDER BY u.email, s.name, m.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group data by user and site
$data = [];
foreach ($results as $row) {
    $data[$row['user_email']][$row['site_name']][] = $row;
}
?>
<div class="container mt-4">
    <h1>Modules Overview</h1>
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="email" class="form-control" placeholder="Filter by email" value="<?php echo htmlspecialchars($filterEmail, ENT_QUOTES); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    <?php if (empty($data)) : ?>
        <p>No modules found.</p>
    <?php else : ?>
        <?php foreach ($data as $userEmail => $sites) : ?>
            <h3><?php echo htmlspecialchars($userEmail); ?></h3>
            <?php foreach ($sites as $siteName => $modules) : ?>
                <h5 class="ms-3"><?php echo htmlspecialchars($siteName); ?></h5>
                <table class="table table-bordered ms-3">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Price per month</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Active</th>
                            <th>Auto-renew</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modules as $module) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                                <td><?php echo htmlspecialchars($module['price_per_month']); ?></td>
                                <td><?php echo htmlspecialchars($module['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($module['end_date']); ?></td>
                                <td><?php echo $module['active'] ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $module['auto_renew'] ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <?php if ($module['active']) : ?>
                                        <a href="?deactivate=<?php echo $module['site_module_id']; ?>" class="btn btn-sm btn-warning" onclick="return confirm('Deactivate this module?');">Deactivate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once("template/footer.php"); ?>
