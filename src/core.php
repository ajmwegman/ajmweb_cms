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

    public static function url(string $path = ''): string
    {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $scheme = $isSecure ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $base = $scheme . $host . ($basePath ? $basePath . '/' : '/');

        return $base . ltrim($path, '/');
    }
}
?>
