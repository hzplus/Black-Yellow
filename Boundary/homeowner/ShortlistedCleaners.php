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
            error_log("Error checking if shortlisted: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add a cleaner to a homeowner's shortlist
     * 
     * @param int $cleanerId The ID of the cleaner to add
     * @param int $homeownerId The ID of the homeowner
     * @return bool Success status
     */
    public function addToShortlist($cleanerId, $homeownerId) {
        try {
            // Check if already shortlisted to avoid duplicates
            if ($this->isShortlisted($cleanerId, $homeownerId)) {
                return true; // Already shortlisted, return success
            }
            
            return $this->platformEntity->addToShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            error_log("Error adding to shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove a cleaner from a homeowner's shortlist
     * 
     * @param int $cleanerId The ID of the cleaner to remove
     * @param int $homeownerId The ID of the homeowner
     * @return bool Success status
     */
    public function removeFromShortlist($cleanerId, $homeownerId) {
        try {
            // Check if actually shortlisted
            if (!$this->isShortlisted($cleanerId, $homeownerId)) {
                return true; // Not shortlisted, return success
            }
            
            return $this->platformEntity->removeFromShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            error_log("Error removing from shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle a cleaner's shortlist status
     * 
     * @param int $cleanerId The ID of the cleaner
     * @param int $homeownerId The ID of the homeowner
     * @return bool Success status
     */
    public function toggleShortlist($cleanerId, $homeownerId) {
        try {
            if ($this->isShortlisted($cleanerId, $homeownerId)) {
                return $this->platformEntity->removeFromShortlist($homeownerId, $cleanerId);
            } else {
                return $this->platformEntity->addToShortlist($homeownerId, $cleanerId);
            }
        } catch (Exception $e) {
            error_log("Error toggling shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    // Get all shortlisted cleaner IDs for a homeowner
    public function getShortlistedCleanerIds($homeownerId) {
        try {
            return $this->platformEntity->getShortlistedCleanerIds($homeownerId);
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaner IDs: " . $e->getMessage());
            return [];
        }
    }
    
    // Get shortlisted cleaners
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
            error_log("Error getting shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    // Search through shortlisted cleaners
    public function searchShortlistedCleaners($homeownerId, $search = '', $category = '') {
        try {
            $allShortlisted = $this->getShortlistedCleaners($homeownerId);
            
            if (empty($search) && empty($category)) {
                return $allShortlisted;
            }
            
            $filteredCleaners = [];
            foreach ($allShortlisted as $cleaner) {
                $matchesSearch = empty($search) || stripos($cleaner->getName(), $search) !== false;
                $matchesCategory = empty($category);
                
                if (!$matchesCategory && !empty($cleaner->getServices())) {
                    foreach ($cleaner->getServices() as $service) {
                        if (stripos($service->getCategory(), $category) !== false) {
                            $matchesCategory = true;
                            break;
                        }
                    }
                }
                
                if ($matchesSearch && $matchesCategory) {
                    $filteredCleaners[] = $cleaner;
                }
            }
            
            return $filteredCleaners;
        } catch (Exception $e) {
            error_log("Error searching shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    // Get all categories
    public function getAllCategories() {
        try {
            return $this->platformEntity->getAllCategories();
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}