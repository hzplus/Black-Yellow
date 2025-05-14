<?php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class ShortlistController {
    private $platformEntity;
    
    public function __construct() {
        $this->platformEntity = new CleaningPlatformEntity();
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        try {
            return $this->platformEntity->isShortlisted($cleanerId, $homeownerId);
        } catch (Exception $e) {
            echo "Error checking if shortlisted: " . $e->getMessage();
            return false;
        }
    }
    
    public function addToShortlist($homeownerId, $cleanerId) {
        try {
            return $this->platformEntity->addToShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            echo "Error adding to shortlist: " . $e->getMessage();
            return false;
        }
    }
    
    public function removeFromShortlist($homeownerId, $cleanerId) {
        try {
            return $this->platformEntity->removeFromShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            echo "Error removing from shortlist: " . $e->getMessage();
            return false;
        }
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        try {
            return $this->platformEntity->getShortlistedCleanerIds($homeownerId);
        } catch (Exception $e) {
            echo "Error getting shortlisted cleaner IDs: " . $e->getMessage();
            return [];
        }
    }
    
    // Added method for getting shortlisted cleaners
    public function getShortlistedCleaners($homeownerId) {
        try {
            // Get IDs of shortlisted cleaners
            $shortlistedIds = $this->platformEntity->getShortlistedCleanerIds($homeownerId);
            
            // If no shortlisted cleaners, return empty array
            if (empty($shortlistedIds)) {
                return [];
            }
            
            // Get cleaner objects for each shortlisted ID
            $shortlistedCleaners = [];
            foreach ($shortlistedIds as $cleanerId) {
                $cleaner = $this->platformEntity->getCleanerById($cleanerId);
                if ($cleaner) {
                    $shortlistedCleaners[] = $cleaner;
                }
            }
            
            return $shortlistedCleaners;
        } catch (Exception $e) {
            echo "Error getting shortlisted cleaners: " . $e->getMessage();
            return [];
        }
    }
    
    // Add this new method for getting all categories
    public function getAllCategories() {
        try {
            return $this->platformEntity->getAllCategories();
        } catch (Exception $e) {
            echo "Error getting categories: " . $e->getMessage();
            return [];
        }
    }
}