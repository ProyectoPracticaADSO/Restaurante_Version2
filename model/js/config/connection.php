<?php
class ConnectionDB {
    private static $host = 'srv1853.hstgr.io';
    private static $db = 'u152493769_restaurante';
    private static $user = 'u152493769_restaurante';
    private static $pass = 'Admin2025a';

    public static function connection() {
        try {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db . ';port=3306';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true,
            ];

            return new PDO($dsn, self::$user, self::$pass, $options);
        } catch (Exception $e) {
            echo "Error de conexión: " . $e->getMessage();              
            return null;
        }
    }
}
?>