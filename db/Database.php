<?php
class Database {
    public static function connect(): mysqli {
        $conn = new mysqli("localhost", "root", "", "cleaning_platform");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
?>