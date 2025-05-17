<?php
// Entity/CleanerProfileEntity.php
require_once __DIR__ . '/../../db/Database.php';

class CleanerProfileEntity {
    /**
     * Get a cleaner by ID
     * 
     * @param int $cleanerId The cleaner ID to retrieve
     * @return array|null Cleaner data array or null if not found
     */
    public function getCleanerById(int $cleanerId): ?array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT u.userid, u.username, u.email, u.status 
                    FROM users u 
                    WHERE u.userid = ? AND u.role = 'Cleaner'";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $cleaner = $result->fetch_assoc();
            
            // Check if cleaner is active
            if ($cleaner['status'] !== 'active') {
                return null;
            }
            
            $stmt->close();
            return $cleaner;
        } catch (\Exception $e) {
            error_log("Error getting cleaner: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all services offered by a cleaner
     * 
     * @param int $cleanerId Cleaner ID
     * @return array Array of service arrays
     */
    public function getCleanerServices(int $cleanerId): array {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ?");
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $services = [];
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
            
            $stmt->close();
            return $services;
        } catch (\Exception $e) {
            error_log("Error getting cleaner services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if cleaner is shortlisted by homeowner
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Whether cleaner is shortlisted
     */
    public function isShortlisted(int $cleanerId, int $homeownerId): bool {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT shortlistid 
                FROM shortlists 
                WHERE homeownerid = ? AND cleanerid = ?
            ");
            
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $isShortlisted = ($result && $result->num_rows > 0);
            $stmt->close();
            
            return $isShortlisted;
        } catch (\Exception $e) {
            error_log("Error checking if shortlisted: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add a cleaner to homeowner's shortlist
     * 
     * @param int $homeownerId Homeowner ID
     * @param int $cleanerId Cleaner ID to add
     * @return bool Success status
     */
    public function addToShortlist(int $homeownerId, int $cleanerId): bool {
        try {
            // Don't add if already shortlisted
            if ($this->isShortlisted($cleanerId, $homeownerId)) {
                return true;
            }
            
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO shortlists (homeownerid, cleanerid) VALUES (?, ?)");
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (\Exception $e) {
            error_log("Error adding to shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove a cleaner from homeowner's shortlist
     * 
     * @param int $homeownerId Homeowner ID
     * @param int $cleanerId Cleaner ID to remove
     * @return bool Success status
     */
    public function removeFromShortlist(int $homeownerId, int $cleanerId): bool {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (\Exception $e) {
            error_log("Error removing from shortlist: " . $e->getMessage());
            return false;
        }
    }
}