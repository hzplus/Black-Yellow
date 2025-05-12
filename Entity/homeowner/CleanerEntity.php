<?php
require_once __DIR__ . '/../../db/Database.php';
require_once __DIR__ . '/ServiceEntity.php';

class Cleaner {
    private $id;
    private $name;
    private $email;
    private $status;
    private $services = [];
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    // For compatibility with ViewCleanerProfile.php
    public function getBio() {
        return "Professional cleaning service provider."; // Default bio
    }
    
    public function getPhone() {
        return null; // Not in the database
    }
    
    public function getProfileImage() {
        return "default.jpg"; // Default image
    }
    
    public function getServices() {
        return $this->services;
    }
    
    public function getAvgPrice() {
        // Calculate the average price of all services
        $totalPrice = 0;
        $count = count($this->services);
        
        if ($count === 0) {
            return 0; // No services, return 0
        }
        
        foreach ($this->services as $service) {
            $totalPrice += $service->getPrice();
        }
        
        return $totalPrice / $count;
    }
    
    // Set services method to help with compatibility
    public function setServices($services) {
        $this->services = $services;
    }
    
    // Database methods
    public function getCleanerById($cleanerId) {
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
        
        $row = $result->fetch_assoc();
        
        // Check if cleaner is active
        if ($row['status'] !== 'active') {
            return null;
        }
        
        $cleaner = new Cleaner();
        $cleaner->id = $row['userid'];
        $cleaner->name = $row['username'];
        $cleaner->email = $row['email'];
        $cleaner->status = $row['status'];
        
        // Get cleaner services
        $service = new CleanerService(null, null, null, null, null, null);
        $services = $service->getServicesByCleaner($cleaner->id);
        
        foreach ($services as $serviceData) {
            $serviceObj = new CleanerService(
                $serviceData['serviceid'],
                $serviceData['cleanerid'],
                $serviceData['title'],
                $serviceData['price'],
                $serviceData['description'],
                $serviceData['availability'],
                $serviceData['category']
            );
            $cleaner->services[] = $serviceObj;
        }
        
        return $cleaner;
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return ($row['count'] > 0);
    }
    
    public function addToShortlist($cleanerId, $homeownerId) {
        // Don't add if already shortlisted
        if ($this->isShortlisted($cleanerId, $homeownerId)) {
            return true;
        }
        
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO shortlists (homeownerid, cleanerid) VALUES (?, ?)");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        return $stmt->execute();
    }
    
    public function removeFromShortlist($cleanerId, $homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        return $stmt->execute();
    }
    
    // Methods for cleaner listings
    public function getAllCleaners($sortBy = 'name') {
        $conn = Database::getConnection();
        $orderClause = $this->getSortClause($sortBy);
        
        $sql = "SELECT u.userid, u.username, 
                (SELECT AVG(price) FROM services WHERE cleanerid = u.userid) as avg_price
                FROM users u 
                WHERE u.role = 'Cleaner' AND u.status = 'active'
                $orderClause";
                
        $result = $conn->query($sql);
        
        return $this->processCleanerResults($result);
    }
    
    public function searchCleaners($searchTerm, $category, $sortBy = 'name') {
        $conn = Database::getConnection();
        $orderClause = $this->getSortClause($sortBy);
        
        $sql = "SELECT DISTINCT u.userid, u.username, 
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
        
        $sql .= " $orderClause";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $this->processCleanerResults($result);
    }
    
    private function getSortClause($sortBy) {
        switch ($sortBy) {
            case 'price':
                return "ORDER BY avg_price ASC";
            case 'name':
            default:
                return "ORDER BY u.username ASC";
        }
    }
    
    private function processCleanerResults($result) {
        $cleaners = [];
        
        while ($row = $result->fetch_assoc()) {
            $cleaner = new Cleaner();
            $cleaner->id = $row['userid'];
            $cleaner->name = $row['username'];
            
            // Get cleaner email
            $conn = Database::getConnection();
            $emailQuery = "SELECT email FROM users WHERE userid = ?";
            $stmt = $conn->prepare($emailQuery);
            $stmt->bind_param("i", $cleaner->id);
            $stmt->execute();
            $emailResult = $stmt->get_result();
            $emailRow = $emailResult->fetch_assoc();
            $cleaner->email = $emailRow['email'];
            
            // Get services
            $service = new CleanerService(null, null, null, null, null, null);
            $services = $service->getServicesByCleaner($cleaner->id);
            
            foreach ($services as $serviceData) {
                $serviceObj = new CleanerService(
                    $serviceData['serviceid'],
                    $serviceData['cleanerid'],
                    $serviceData['title'],
                    $serviceData['price'],
                    $serviceData['description'],
                    $serviceData['availability'],
                    $serviceData['category']
                );
                $cleaner->services[] = $serviceObj;
            }
            
            $cleaners[] = $cleaner;
        }
        
        return $cleaners;
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT cleanerid FROM shortlists WHERE homeownerid = ?");
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $shortlistedIds = [];
        while ($row = $result->fetch_assoc()) {
            $shortlistedIds[] = $row['cleanerid'];
        }
        
        return $shortlistedIds;
    }
}