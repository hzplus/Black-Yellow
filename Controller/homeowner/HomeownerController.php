<?php
require_once(__DIR__ . '/../../Entity/homeowner/Cleaner.php');
require_once(__DIR__ . '/../../db/Database.php');
require_once(__DIR__ . '/../../Entity/homeowner/Service.php');
require_once(__DIR__ . '/CleanerListingController.php');
require_once(__DIR__ . '/ShortlistController.php');
require_once(__DIR__ . '/ServiceController.php');

class HomeownerController {
    
    private $db;
    private $cleanerListingController;
    private $shortlistController;
    private $serviceController;

    public function __construct() {
        $this->db = Database::connect();
        $this->cleanerListingController = new CleanerListingController();
        $this->shortlistController = new ShortlistController();
        $this->serviceController = new ServiceController();
    }

    // Fetch all cleaners with their services
    public function getCleaners() {
        return $this->cleanerListingController->getCleaners();
    }

    // Get a specific cleaner by ID
    public function getCleanerById($cleanerId) {
        return $this->cleanerListingController->getCleanerById($cleanerId);
    }

    // Get all shortlisted cleaners for a homeowner
    public function getShortlistedCleaners($homeownerId) {
        return $this->shortlistController->getShortlistedCleaners($homeownerId);
    }

    // Toggle shortlist status for a cleaner
    public function toggleShortlist($cleanerId, $homeownerId) {
        return $this->shortlistController->toggleShortlist($cleanerId, $homeownerId);
    }

    // Check if a cleaner is shortlisted by a specific homeowner
    public function isCleanerShortlistedByUser($cleanerId, $homeownerId) {
        return $this->shortlistController->isCleanerShortlistedByUser($cleanerId, $homeownerId);
    }
    
    // Remove a cleaner from the shortlist
    public function removeFromShortlist($cleanerId, $homeownerId) {
        return $this->shortlistController->removeFromShortlist($cleanerId, $homeownerId);
    }
    
    // Add a cleaner to the shortlist
    public function addCleanerToShortlist($homeownerId, $cleanerId) {
        return $this->shortlistController->addToShortlist($cleanerId, $homeownerId);
    }
    
    // Get service history for a homeowner
    public function getServiceHistory($homeownerId) {
        $query = "
            SELECT m.*, s.title, s.price, u.username as cleaner_name
            FROM match_history m
            JOIN services s ON m.serviceid = s.serviceid
            JOIN users u ON m.cleanerid = u.userid
            WHERE m.homeownerid = ?
            ORDER BY m.match_date DESC
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $history = [];
        
        while ($row = $result->fetch_assoc()) {
            $history[] = [
                'matchid' => $row['matchid'],
                'cleaner_id' => $row['cleanerid'],
                'cleaner_name' => $row['cleaner_name'],
                'service_title' => $row['title'],
                'price' => $row['price'],
                'datetime' => $row['match_date'],
                'summary' => 'Service: ' . $row['title'] . ' by ' . $row['cleaner_name']
            ];
        }
        
        $stmt->close();
        return $history;
    }
    
    // Search cleaners by name
    public function searchCleanersByName($searchTerm) {
        return $this->cleanerListingController->searchCleanersByName($searchTerm);
    }
    
    // Search cleaners by service
    public function searchCleanersByService($searchTerm) {
        return $this->cleanerListingController->searchCleanersByService($searchTerm);
    }
    
    // Get service details by ID
    public function getServiceById($serviceId) {
        return $this->serviceController->getServiceById($serviceId);
    }
    
    // Increment service view count
    public function incrementServiceViewCount($serviceId) {
        return $this->serviceController->incrementViewCount($serviceId);
    }
    
    // Get popular services
    public function getPopularServices($limit = 5) {
        return $this->serviceController->getPopularServices($limit);
    }
    
    // Book a service
    public function bookService($homeownerId, $cleanerId, $serviceId, $bookingDate) {
        $query = "
            INSERT INTO match_history (cleanerid, homeownerid, serviceid, match_date) 
            VALUES (?, ?, ?, ?)
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiis", $cleanerId, $homeownerId, $serviceId, $bookingDate);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}