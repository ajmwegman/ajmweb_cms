<?php
require_once __DIR__ . '/database.class.php';

class Core
{
    private static $db;
    private static $config = [];

    public static function init(array $config)
    {
        self::$config = $config;

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4',
            $config['db_host'],
            $config['db_name']
        );
        $pdo = new PDO($dsn, $config['db_user'], $config['db_password']);
        self::$db = new database($pdo);
    }

    public static function db()
    {
        return self::$db;
    }

    public static function config(?string $key = null)
    {
        if ($key === null) {
            return self::$config;
        }
        return self::$config[$key] ?? null;
    }
}
?>
