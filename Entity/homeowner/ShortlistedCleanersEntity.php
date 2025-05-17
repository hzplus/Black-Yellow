<?php
// Entity/ShortlistedCleanersEntity.php
require_once __DIR__ . '/../../db/Database.php';

class ShortlistedCleanersEntity {
    /**
     * Get all shortlisted cleaners for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of cleaner objects
     */
    public function getShortlistedCleaners(int $homeownerId): array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT u.userid, u.username, u.email
                    FROM shortlists s
                    JOIN users u ON s.cleanerid = u.userid
                    WHERE s.homeownerid = ? AND u.status = 'active'
                    ORDER BY u.username";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cleaners = [];
            while ($row = $result->fetch_assoc()) {
                $cleaners[] = $row;
            }
            
            $stmt->close();
            return $cleaners;
        } catch (\Exception $e) {
            error_log("Error getting shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search through shortlisted cleaners
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $search Search term
     * @param string $category Optional category filter
     * @return array Array of matching cleaner objects
     */
    public function searchShortlisted(int $homeownerId, string $search = '', string $category = ''): array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT DISTINCT u.userid, u.username, u.email
                    FROM shortlists sh
                    JOIN users u ON sh.cleanerid = u.userid
                    LEFT JOIN services s ON u.userid = s.cleanerid
                    WHERE sh.homeownerid = ? AND u.status = 'active'";
            
            $params = [$homeownerId];
            $types = "i";
            
            if (!empty($search)) {
                $sql .= " AND u.username LIKE ?";
                $params[] = "%$search%";
                $types .= "s";
            }
            
            if (!empty($category)) {
                $sql .= " AND s.category = ?";
                $params[] = $category;
                $types .= "s";
            }
            
            $sql .= " ORDER BY u.username";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cleaners = [];
            while ($row = $result->fetch_assoc()) {
                $cleaners[] = $row;
            }
            
            $stmt->close();
            return $cleaners;
        } catch (\Exception $e) {
            error_log("Error searching shortlisted cleaners: " . $e->getMessage());
            return [];
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
    
    /**
     * Get all services offered by a cleaner
     * 
     * @param int $cleanerId Cleaner ID
     * @return array Array of service objects
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
     * Get all available service categories
     * 
     * @return array Array of category names
     */
    public function getAllCategories(): array {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT name FROM service_categories");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['name'];
            }
            
            $stmt->close();
            return $categories;
        } catch (\Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}   