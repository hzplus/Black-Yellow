<?php
// Entity/BrowseCleanersEntity.php
require_once __DIR__ . '/../db/Database.php';

class BrowseCleanersEntity {
    /**
     * Get all active cleaners
     * 
     * @param string $sortBy Column to sort by ('name' or 'price')
     * @return array Array of cleaner objects
     */
    public function getAllCleaners(string $sortBy = 'name'): array {
        try {
            $conn = Database::getConnection();
            
            // Determine sort order
            $orderBy = "u.username ASC"; // Default sort by name
            if ($sortBy === 'price') {
                $orderBy = "(SELECT AVG(price) FROM services WHERE cleanerid = u.userid) ASC";
            }
            
            $sql = "SELECT u.userid, u.username, u.email,
                    (SELECT AVG(price) FROM services WHERE cleanerid = u.userid) as avg_price
                    FROM users u 
                    WHERE u.role = 'Cleaner' AND u.status = 'active'
                    ORDER BY $orderBy";
                    
            $result = $conn->query($sql);
            
            $cleaners = [];
            while ($row = $result->fetch_assoc()) {
                $cleaners[] = $row;
            }
            
            return $cleaners;
        } catch (\Exception $e) {
            error_log("Error getting all cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search for cleaners by name or category
     * 
     * @param string $searchTerm The search term
     * @param string $category Optional category filter
     * @param string $sortBy Column to sort by ('name' or 'price')
     * @return array Array of matching cleaner objects
     */
    public function searchCleaners(string $searchTerm, string $category = '', string $sortBy = 'name'): array {
        try {
            $conn = Database::getConnection();
            
            // Determine sort order
            $orderBy = "u.username ASC"; // Default sort by name
            if ($sortBy === 'price') {
                $orderBy = "(SELECT AVG(price) FROM services WHERE cleanerid = u.userid) ASC";
            }
            
            $sql = "SELECT DISTINCT u.userid, u.username, u.email,
                    (SELECT AVG(price) FROM services WHERE cleanerid = u.userid) as avg_price
                    FROM users u 
                    LEFT JOIN services s ON u.userid = s.cleanerid
                    WHERE u.role = 'Cleaner' AND u.status = 'active'";
            
            $params = [];
            $types = "";
            
            if (!empty($searchTerm)) {
                $searchTerm = "%" . $searchTerm . "%";
                $sql .= " AND u.username LIKE ?";
                $params[] = $searchTerm;
                $types .= "s";
            }
            
            if (!empty($category)) {
                $sql .= " AND s.category = ?";
                $params[] = $category;
                $types .= "s";
            }
            
            $sql .= " ORDER BY $orderBy";
            
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cleaners = [];
            while ($row = $result->fetch_assoc()) {
                $cleaners[] = $row;
            }
            
            $stmt->close();
            return $cleaners;
        } catch (\Exception $e) {
            error_log("Error searching cleaners: " . $e->getMessage());
            return [];
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
    
    /**
     * Get IDs of all shortlisted cleaners for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of cleaner IDs
     */
    public function getShortlistedCleanerIds(int $homeownerId): array {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT cleanerid FROM shortlists WHERE homeownerid = ?");
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $shortlistedIds = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $shortlistedIds[] = $row['cleanerid'];
                }
            }
            
            $stmt->close();
            return $shortlistedIds;
        } catch (\Exception $e) {
            error_log("Error getting shortlisted cleaner IDs: " . $e->getMessage());
            return [];
        }
    }
}