<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: /users/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>Aanmelden</title>
</head>
<body>
<?php require __DIR__ . '/view.php'; ?>
</body>
</html>
