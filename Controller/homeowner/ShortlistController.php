<?php
require_once(__DIR__ . '/../../Entity/homeowner/Cleaner.php');
require_once(__DIR__ . '/../../db/Database.php');
require_once(__DIR__ . '/../../Entity/homeowner/Service.php');
require_once(__DIR__ . '/CleanerListingController.php');

class ShortlistController {
    
    private $db;
    private $cleanerListingController;

    public function __construct() {
        $this->db = Database::connect();
        $this->cleanerListingController = new CleanerListingController();
    }

    public function getShortlistedCleaners($homeownerId) {
        $query = "
            SELECT u.userid, u.username, u.email, u.status
            FROM users u
            INNER JOIN shortlists sc ON u.userid = sc.cleanerid
            WHERE u.role = 'cleaner' AND sc.homeownerid = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $cleaners = [];

        while ($row = $result->fetch_assoc()) {
            $cleanerId = $row['userid'];
            
            // Get the full cleaner details including services
            $cleaner = $this->cleanerListingController->getCleanerById($cleanerId);
            if ($cleaner) {
                $cleaners[] = $cleaner;
            }
        }

        $stmt->close();
        return $cleaners;
    }

    public function toggleShortlist($cleanerId, $homeownerId) {
        try {
            // Step 1: Check if the cleaner is already shortlisted by the homeowner
            $query = "SELECT 1 FROM shortlists WHERE cleanerid = ? AND homeownerid = ?";
            $stmt = $this->db->prepare($query);
            
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ii", $cleanerId, $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Step 2: If the cleaner is already shortlisted, delete the entry
            if ($result->num_rows > 0) {
                $query = "DELETE FROM shortlists WHERE cleanerid = ? AND homeownerid = ?";
                $action = "remove";
            } else {
                // Step 3: Otherwise, insert a new entry
                $query = "INSERT INTO shortlists (cleanerid, homeownerid) VALUES (?, ?)";
                
                // Also update the shortlist_count in the services table
                $updateCountQuery = "UPDATE services SET shortlist_count = shortlist_count + 1 WHERE cleanerid = ?";
                $updateStmt = $this->db->prepare($updateCountQuery);
                $updateStmt->bind_param("i", $cleanerId);
                $updateStmt->execute();
                $updateStmt->close();
                
                $action = "add";
            }
            
            // Close the first statement before preparing a new one
            $stmt->close();
            
            // Prepare and execute the query to either insert or delete
            $stmt = $this->db->prepare($query);
            
            if (!$stmt) {
                error_log("Prepare failed: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ii", $cleanerId, $homeownerId);
            $success = $stmt->execute();
            
            if (!$success) {
                error_log("Execute failed: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return $action; // Return whether we added or removed
        } catch (Exception $e) {
            error_log("Exception in toggleShortlist: " . $e->getMessage());
            return false;
        }
    }

    public function isCleanerShortlistedByUser($cleanerId, $homeownerId) {
        // Query the database to check if the cleaner is shortlisted
        $query = "SELECT 1 FROM shortlists WHERE cleanerid = ? AND homeownerid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cleanerId, $homeownerId);
        
        // Check for errors while executing
        if (!$stmt->execute()) {
            // Handle execution error (log, throw exception, etc.)
            return false;
        }
        
        // Get the result and check if there's an existing record
        $result = $stmt->get_result();
        $isShortlisted = $result->num_rows > 0;
        $stmt->close();
        
        return $isShortlisted;
    }
    
    // For backward compatibility
    public function removeFromShortlist($cleanerId, $homeownerId) {
        $query = "DELETE FROM shortlists WHERE cleanerid = ? AND homeownerid = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cleanerId, $homeownerId);
        $success = $stmt->execute();
        $stmt->close();
        
        if ($success) {
            // Also update the shortlist_count in the services table
            $updateCountQuery = "UPDATE services SET shortlist_count = shortlist_count - 1 WHERE cleanerid = ? AND shortlist_count > 0";
            $updateStmt = $this->db->prepare($updateCountQuery);
            $updateStmt->bind_param("i", $cleanerId);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        return $success;
    }
    
    public function addToShortlist($cleanerId, $homeownerId) {
        // Check if already shortlisted
        if ($this->isCleanerShortlistedByUser($cleanerId, $homeownerId)) {
            return true;
        }
        
        $query = "INSERT INTO shortlists (cleanerid, homeownerid) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cleanerId, $homeownerId);
        $success = $stmt->execute();
        $stmt->close();
        
        if ($success) {
            // Also update the shortlist_count in the services table
            $updateCountQuery = "UPDATE services SET shortlist_count = shortlist_count + 1 WHERE cleanerid = ?";
            $updateStmt = $this->db->prepare($updateCountQuery);
            $updateStmt->bind_param("i", $cleanerId);
            $updateStmt->execute();
            $updateStmt->close();
        }
        
        return $success;
    }
}
?>