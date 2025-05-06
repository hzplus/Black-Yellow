<?php
require_once 'db.php';
require_once __DIR__ . '/../Entity/Cleaner.php';

class CleanerDAO {
    private $conn;
    
    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
    }
    
    // Get all cleaners
    public function getAllCleaners() {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.username, u.email, u.phone 
            FROM cleaners c
            JOIN users u ON c.userid = u.userid
            WHERE u.role = 'Cleaner'
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $cleaners = [];
        
        while ($row = $result->fetch_assoc()) {
            $cleaner = $this->mapResultToCleaner($row);
            $cleaners[] = $cleaner;
        }
        
        return $cleaners;
    }
    
    // Search cleaners by name or service
    public function searchCleaners($searchTerm, $searchBy = 'name') {
        $query = "
            SELECT c.*, u.username, u.email, u.phone 
            FROM cleaners c
            JOIN users u ON c.userid = u.userid
            WHERE u.role = 'Cleaner'
        ";
        
        if ($searchBy === 'name') {
            $query .= " AND u.username LIKE ?";
            $searchTerm = "%" . $searchTerm . "%";
        } else if ($searchBy === 'service') {
            $query .= " AND (c.service1 LIKE ? OR c.service2 LIKE ? OR c.service3 LIKE ?)";
            $searchTerm = "%" . $searchTerm . "%";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($searchBy === 'name') {
            $stmt->bind_param("s", $searchTerm);
        } else if ($searchBy === 'service') {
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $cleaners = [];
        
        while ($row = $result->fetch_assoc()) {
            $cleaner = $this->mapResultToCleaner($row);
            $cleaners[] = $cleaner;
        }
        
        return $cleaners;
    }
    
    // Get cleaner by ID
    public function getCleanerById($cleanerId) {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.username, u.email, u.phone, u.address 
            FROM cleaners c
            JOIN users u ON c.userid = u.userid
            WHERE c.userid = ?
        ");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $this->mapResultToCleaner($row);
        }
        
        return null;
    }
    
    // Helper function to map db result to Cleaner object
    private function mapResultToCleaner($row) {
        $cleaner = new Cleaner();
        $cleaner->userid = $row['userid'];
        $cleaner->username = $row['username'];
        $cleaner->email = $row['email'];
        $cleaner->phone = $row['phone'];
        $cleaner->bio = isset($row['bio']) ? $row['bio'] : '';
        $cleaner->rating = isset($row['rating']) ? $row['rating'] : 0;
        $cleaner->availability = isset($row['availability']) ? $row['availability'] : '';
        $cleaner->service1 = isset($row['service1']) ? $row['service1'] : '';
        $cleaner->price1 = isset($row['price1']) ? $row['price1'] : 0;
        $cleaner->service2 = isset($row['service2']) ? $row['service2'] : '';
        $cleaner->price2 = isset($row['price2']) ? $row['price2'] : 0;
        $cleaner->service3 = isset($row['service3']) ? $row['service3'] : '';
        $cleaner->price3 = isset($row['price3']) ? $row['price3'] : 0;
        $cleaner->address = isset($row['address']) ? $row['address'] : '';
        
        return $cleaner;
    }
    
    // Update cleaner rating
    public function updateRating($cleanerId, $newRating) {
        $stmt = $this->conn->prepare("UPDATE cleaners SET rating = ? WHERE userid = ?");
        $stmt->bind_param("di", $newRating, $cleanerId);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>