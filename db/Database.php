<?php
class Database {
    private static $conn = null;

    public static function connect() {
        if (self::$conn == null) {
            self::$conn = new mysqli("localhost", "root", "", "cleaning_platform");
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);  // Will stop script execution
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

    // You can define your data fetching methods here as well
    public static function fetchCleaners() {
        $conn = self::connect();
        $result = $conn->query("SELECT * FROM cleaners");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function fetchCleanerById($id) {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT * FROM cleaners WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Example function to add a cleaner to the shortlist
    public static function addCleanerToShortlist($cleanerId) {
        $conn = self::connect();
        $stmt = $conn->prepare("INSERT INTO shortlisted_cleaners (cleaner_id) VALUES (?)");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
    }

    // Example service history function
    public static function fetchServiceHistory($homeownerId) {
        $conn = self::connect();
        $stmt = $conn->prepare("SELECT * FROM service_history WHERE homeowner_id = ?");
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>