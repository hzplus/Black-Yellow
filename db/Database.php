<?php
class Database {
    private static $conn = null;

    // Establishing the connection
    public static function connect() {
        if (self::$conn == null) {
            self::$conn = new mysqli("localhost", "root", "", "cleaning_platform");
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    // Closing the connection
    public static function disconnect() {
        if (self::$conn != null) {
            self::$conn->close();
            self::$conn = null;
        }
    }

    // Fetch all cleaners (from the user table) along with their services
    public static function fetchCleaners() {
        $sql = "SELECT u.userid, u.username, u.email, s.serviceid, s.title, s.price, s.availability, s.description 
                FROM users u
                LEFT JOIN services s ON u.userid = s.cleanerid
                WHERE u.role = 'cleaner'"; // Filter by the role of cleaner
    
        $conn = self::connect();
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if (!$result) {
            die("Error in query: " . $conn->error);
        }

        $cleaners = [];
    
        while ($row = $result->fetch_assoc()) {
            $userId = $row['userid'];
    
            // Initialize cleaner if not already in the list
            if (!isset($cleaners[$userId])) {
                $cleaners[$userId] = [
                    'userid' => $userId,
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'services' => []
                ];
            }
    
            // Add service only if it exists (i.e. not null due to LEFT JOIN)
            if ($row['serviceid']) {
                $cleaners[$userId]['services'][] = [
                    'serviceid' => $row['serviceid'],
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'availability' => $row['availability'],
                    'description' => $row['description']
                ];
            }
        }
    
        return array_values($cleaners); // Reset keys to numeric array
    }

    // Fetch all shortlisted cleaners
    public static function fetchShortlistedCleaners() {
        $conn = self::connect();
        $query = "
            SELECT u.* 
            FROM users u
            JOIN shortlisted_cleaners s ON u.id = s.cleaner_id
            WHERE u.role = 'cleaner'
        ";
        
        // Execute the query
        $result = $conn->query($query);
        
        // Check if the query was successful
        if (!$result) {
            die("Error in query: " . $conn->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add cleaner to the shortlist
    public static function addCleanerToShortlist($cleanerId) {
        $conn = self::connect();
        // Prevent duplicates
        $check = $conn->prepare("SELECT * FROM shortlisted_cleaners WHERE cleaner_id = ?");
        $check->bind_param("i", $cleanerId);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO shortlisted_cleaners (cleaner_id) VALUES (?)");
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
        }
    }

    // Remove cleaner from shortlist
    public static function removeCleanerFromShortlist($cleanerId) {
        $conn = self::connect();
        $stmt = $conn->prepare("DELETE FROM shortlisted_cleaners WHERE cleaner_id = ?");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
    }
}