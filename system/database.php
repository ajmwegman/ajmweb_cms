<?php
$db_user 	 = 'ajmweb_wm';  
$db_password = 'ASDFGH01';
$host 		 = 'localhost';
$db_name 	 = 'ajmweb_wm';

/* setup pdo */
$port = "3306";
$charset = 'utf8mb4';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset;port=$port";
try {
     $pdo = new \PDO($dsn, $db_user, $db_password, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>