<?php
require_once 'db.php';
require_once __DIR__ . '/../Entity/Shortlist.php';
require_once __DIR__ . '/../Entity/Cleaner.php';
require_once 'CleanerDAO.php';

class ShortlistDAO {
    private $conn;
    private $cleanerDAO;
    
    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
        $this->cleanerDAO = new CleanerDAO();
    }
    
    // Add cleaner to shortlist
    public function addToShortlist($homeownerId, $cleanerId) {
        // Check if already exists
        if ($this->isShortlisted($homeownerId, $cleanerId)) {
            return false;
        }
        
        $stmt = $this->conn->prepare("INSERT INTO shortlist (homeowner_id, cleaner_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Remove cleaner from shortlist
    public function removeFromShortlist($homeownerId, $cleanerId) {
        $stmt = $this->conn->prepare("DELETE FROM shortlist WHERE homeowner_id = ? AND cleaner_id = ?");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Check if cleaner is already shortlisted
    public function isShortlisted($homeownerId, $cleanerId) {
        $stmt = $this->conn->prepare("SELECT * FROM shortlist WHERE homeowner_id = ? AND cleaner_id = ?");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Get all shortlisted cleaners for a homeowner
    public function getShortlistedCleaners($homeownerId) {
        $stmt = $this->conn->prepare("
            SELECT s.cleaner_id 
            FROM shortlist s
            WHERE s.homeowner_id = ?
        ");
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cleaners = [];
        while ($row = $result->fetch_assoc()) {
            $cleaner = $this->cleanerDAO->getCleanerById($row['cleaner_id']);
            if ($cleaner) {
                $cleaners[] = $cleaner;
            }
        }
        
        return $cleaners;
    }
}
?>