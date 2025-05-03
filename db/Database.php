<?php
class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn == null) {
            self::$conn = new mysqli("localhost", "root", "", "cleaning_platform");
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    public static function disconnect() {
        if (self::$conn != null) {
            self::$conn->close();
            self::$conn = null;
        }
    }
}
?>
