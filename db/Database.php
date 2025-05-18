<?php
class Database {
    private static $host = 'localhost';
    private static $dbName = 'cleaning_platform';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$dbName);
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
