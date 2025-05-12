<?php
// Controller/homeowner/ShortlistController.php
require_once(__DIR__ . '/../../Entity/homeowner/CleanerEntity.php');
require_once(__DIR__ . '/../../Entity/homeowner/ServiceEntity.php');
require_once(__DIR__ . '/../../Entity/homeowner/ShortlistEntity.php');

class ShortlistController {
    private $cleanerEntity;
    private $serviceEntity;
    private $shortlistEntity;
    
    public function __construct() {
        $this->cleanerEntity = new Cleaner();
        $this->serviceEntity = new CleanerService(null, null, null, null, null, null);
        $this->shortlistEntity = new ShortlistEntity();
    }
    
    public function getShortlistedCleaners($homeownerId) {
        try {
            $shortlistedIds = $this->shortlistEntity->getShortlistedCleanerIds($homeownerId);
            $cleaners = [];
            
            foreach ($shortlistedIds as $cleanerId) {
                $cleaner = $this->cleanerEntity->getCleanerById($cleanerId);
                if ($cleaner) {
                    $cleaners[] = $cleaner;
                }
            }
            
            return $cleaners;
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    public function searchShortlistedCleaners($homeownerId, $search, $category) {
        try {
            $shortlistedIds = $this->shortlistEntity->getShortlistedCleanerIds($homeownerId);
            
            // If no shortlisted cleaners, return empty array
            if (empty($shortlistedIds)) {
                return [];
            }
            
            // Get all cleaners matching search and category
            $allMatchingCleaners = $this->cleanerEntity->searchCleaners($search, $category);
            
            // Filter to only include shortlisted cleaners
            $shortlistedCleaners = [];
            foreach ($allMatchingCleaners as $cleaner) {
                if (in_array($cleaner->getId(), $shortlistedIds)) {
                    $shortlistedCleaners[] = $cleaner;
                }
            }
            
            return $shortlistedCleaners;
        } catch (Exception $e) {
            error_log("Error searching shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllCategories() {
        try {
            return $this->serviceEntity->getAllCategories();
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    public function addToShortlist($cleanerId, $homeownerId) {
        try {
            return $this->shortlistEntity->addToShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            error_log("Error adding to shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeFromShortlist($cleanerId, $homeownerId) {
        try {
            return $this->shortlistEntity->removeFromShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            error_log("Error removing from shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    // This method handles form submissions from ViewCleanerListings.php
    public function processShortlistAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cleanerId = $_POST['cleaner_id'] ?? 0;
            $homeownerId = $_POST['homeowner_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            
            if (!$cleanerId || !$homeownerId) {
                return false;
            }
            
            if ($action === 'add') {
                return $this->addToShortlist($cleanerId, $homeownerId);
            } else if ($action === 'remove') {
                return $this->removeFromShortlist($cleanerId, $homeownerId);
            }
        }
        
        return false;
    }
}
?>