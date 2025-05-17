<?php
require_once(__DIR__ . '/../../Entity/homeowner/Service.php');
require_once(__DIR__ . '/../../db/Database.php');

class ServiceController {
    
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getServiceById($serviceId) {
        // Check if the columns view_count and shortlist_count exist in the services table
        $checkColumnsQuery = "SHOW COLUMNS FROM services WHERE Field IN ('view_count', 'shortlist_count', 'category', 'image_path')";
        $checkColumnsResult = $this->db->query($checkColumnsQuery);
        
        $existingColumns = [];
        if ($checkColumnsResult) {
            while ($col = $checkColumnsResult->fetch_assoc()) {
                $existingColumns[] = $col['Field'];
            }
        }
        
        // Build a query based on existing columns
        $query = "
            SELECT s.serviceid, s.cleanerid, s.title, s.description, s.price, s.availability";
        
        if (in_array('category', $existingColumns)) {
            $query .= ", s.category";
        }
        if (in_array('image_path', $existingColumns)) {
            $query .= ", s.image_path";
        }
        if (in_array('view_count', $existingColumns)) {
            $query .= ", s.view_count";
        }
        if (in_array('shortlist_count', $existingColumns)) {
            $query .= ", s.shortlist_count";
        }
        
        $query .= ", u.username as cleaner_name, u.email as cleaner_email
            FROM services s
            JOIN users u ON s.cleanerid = u.userid
            WHERE s.serviceid = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $service = new Service();
        $service->setId($row['serviceid']);
        $service->setTitle($row['title']);
        $service->setDescription($row['description']);
        $service->setPrice($row['price']);
        $service->setAvailability($row['availability']);
        $service->setCategory($row['category'] ?? null);
        $service->setImagePath($row['image_path'] ?? null);
        $service->setCleanerId($row['cleanerid']);
        $service->setCleanerName($row['cleaner_name']);
        $service->setCleanerEmail($row['cleaner_email']);
        $service->setCleanerProfilePicture('default.jpg'); // Default value
        $service->setViewCount($row['view_count'] ?? 0);
        $service->setShortlistCount($row['shortlist_count'] ?? 0);
        
        $stmt->close();
        return $service;
    }

    public function incrementViewCount($serviceId) {
        // Check if view_count column exists
        $checkColumnQuery = "SHOW COLUMNS FROM services LIKE 'view_count'";
        $checkResult = $this->db->query($checkColumnQuery);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            $query = "UPDATE services SET view_count = view_count + 1 WHERE serviceid = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $serviceId);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        
        // If the column doesn't exist, we might want to alter the table to add it
        return false;
    }
    
    public function getServicesByCategory($category) {
        // Check if necessary columns exist
        $checkColumnsQuery = "SHOW COLUMNS FROM services WHERE Field IN ('category', 'view_count', 'shortlist_count', 'image_path')";
        $checkColumnsResult = $this->db->query($checkColumnsQuery);
        
        $existingColumns = [];
        if ($checkColumnsResult) {
            while ($col = $checkColumnsResult->fetch_assoc()) {
                $existingColumns[] = $col['Field'];
            }
        }
        
        // If category doesn't exist, we can't filter by it
        if (!in_array('category', $existingColumns)) {
            return [];
        }
        
        $query = "
            SELECT s.serviceid, s.cleanerid, s.title, s.description, s.price, s.availability, s.category";
        
        if (in_array('image_path', $existingColumns)) {
            $query .= ", s.image_path";
        }
        if (in_array('view_count', $existingColumns)) {
            $query .= ", s.view_count";
        }
        if (in_array('shortlist_count', $existingColumns)) {
            $query .= ", s.shortlist_count";
        }
        
        $query .= ", u.username as cleaner_name
            FROM services s
            JOIN users u ON s.cleanerid = u.userid
            WHERE s.category = ?";
        
        if (in_array('shortlist_count', $existingColumns) && in_array('view_count', $existingColumns)) {
            $query .= " ORDER BY s.shortlist_count DESC, s.view_count DESC";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $service = new Service();
            $service->setId($row['serviceid']);
            $service->setTitle($row['title']);
            $service->setDescription($row['description']);
            $service->setPrice($row['price']);
            $service->setAvailability($row['availability']);
            $service->setCategory($row['category']);
            $service->setImagePath($row['image_path'] ?? null);
            $service->setCleanerId($row['cleanerid']);
            $service->setCleanerName($row['cleaner_name']);
            $service->setViewCount($row['view_count'] ?? 0);
            $service->setShortlistCount($row['shortlist_count'] ?? 0);
            
            $services[] = $service;
        }
        
        $stmt->close();
        return $services;
    }
    
    public function getPopularServices($limit = 5) {
        // Check if necessary columns exist
        $checkColumnsQuery = "SHOW COLUMNS FROM services WHERE Field IN ('category', 'view_count', 'shortlist_count', 'image_path')";
        $checkColumnsResult = $this->db->query($checkColumnsQuery);
        
        $existingColumns = [];
        if ($checkColumnsResult) {
            while ($col = $checkColumnsResult->fetch_assoc()) {
                $existingColumns[] = $col['Field'];
            }
        }
        
        $query = "
            SELECT s.serviceid, s.cleanerid, s.title, s.description, s.price, s.availability";
        
        if (in_array('category', $existingColumns)) {
            $query .= ", s.category";
        }
        if (in_array('image_path', $existingColumns)) {
            $query .= ", s.image_path";
        }
        if (in_array('view_count', $existingColumns)) {
            $query .= ", s.view_count";
        }
        if (in_array('shortlist_count', $existingColumns)) {
            $query .= ", s.shortlist_count";
        }
        
        $query .= ", u.username as cleaner_name
            FROM services s
            JOIN users u ON s.cleanerid = u.userid";
        
        if (in_array('shortlist_count', $existingColumns) && in_array('view_count', $existingColumns)) {
            $query .= " ORDER BY s.shortlist_count DESC, s.view_count DESC";
        }
        
        $query .= " LIMIT ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $service = new Service();
            $service->setId($row['serviceid']);
            $service->setTitle($row['title']);
            $service->setDescription($row['description']);
            $service->setPrice($row['price']);
            $service->setAvailability($row['availability']);
            $service->setCategory($row['category'] ?? null);
            $service->setImagePath($row['image_path'] ?? null);
            $service->setCleanerId($row['cleanerid']);
            $service->setCleanerName($row['cleaner_name']);
            $service->setViewCount($row['view_count'] ?? 0);
            $service->setShortlistCount($row['shortlist_count'] ?? 0);
            
            $services[] = $service;
        }
        
        $stmt->close();
        return $services;
    }
    
    // Helper method to add missing columns if needed
    public function addMissingColumns() {
        // Check for missing columns
        $checkColumnsQuery = "SHOW COLUMNS FROM services WHERE Field IN ('view_count', 'shortlist_count', 'category', 'image_path')";
        $checkColumnsResult = $this->db->query($checkColumnsQuery);
        
        $existingColumns = [];
        if ($checkColumnsResult) {
            while ($col = $checkColumnsResult->fetch_assoc()) {
                $existingColumns[] = $col['Field'];
            }
        }
        
        // Add missing columns
        $alterQueries = [];
        
        if (!in_array('view_count', $existingColumns)) {
            $alterQueries[] = "ALTER TABLE services ADD COLUMN view_count INT DEFAULT 0";
        }
        
        if (!in_array('shortlist_count', $existingColumns)) {
            $alterQueries[] = "ALTER TABLE services ADD COLUMN shortlist_count INT DEFAULT 0";
        }
        
        if (!in_array('category', $existingColumns)) {
            $alterQueries[] = "ALTER TABLE services ADD COLUMN category VARCHAR(50) DEFAULT 'General'";
        }
        
        if (!in_array('image_path', $existingColumns)) {
            $alterQueries[] = "ALTER TABLE services ADD COLUMN image_path VARCHAR(255) DEFAULT NULL";
        }
        
        $results = [];
        foreach ($alterQueries as $query) {
            $results[] = $this->db->query($query);
        }
        
        return $results;
    }
}
?>