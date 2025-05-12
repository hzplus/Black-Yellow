<?php
// Controller/homeowner/CleanerListingsController.php
require_once(__DIR__ . '/../../Entity/homeowner/CleanerEntity.php'); 
require_once(__DIR__ . '/../../Entity/homeowner/ServiceEntity.php');
require_once(__DIR__ . '/../../Entity/homeowner/ShortlistEntity.php');

class CleanerListingsController {
    private $cleanerEntity;
    private $serviceEntity;
    private $shortlistEntity;
    
    public function __construct() {
        $this->cleanerEntity = new Cleaner();
        $this->serviceEntity = new CleanerService(null, null, null, null, null, null);
        $this->shortlistEntity = new ShortlistEntity();
    }
    
    public function getAllCleaners($sortBy = 'name') {
        try {
            $cleaners = $this->cleanerEntity->getAllCleaners($sortBy);
            // Get services for each cleaner
            foreach ($cleaners as $cleaner) {
                // Services are already loaded in the Cleaner class's getAllCleaners method
                // No need to set them again
            }
            return $cleaners;
        } catch (Exception $e) {
            echo "Error getting cleaners: " . $e->getMessage();
            return [];
        }
    }
    
    public function searchCleaners($search, $category, $sortBy = 'name') {
        try {
            $cleaners = $this->cleanerEntity->searchCleaners($search, $category, $sortBy);
            // Get services for each cleaner
            foreach ($cleaners as $cleaner) {
                // Services are already loaded in the Cleaner class's searchCleaners method
                // No need to set them again
            }
            return $cleaners;
        } catch (Exception $e) {
            echo "Error searching cleaners: " . $e->getMessage();
            return [];
        }
    }
    
    public function getAllCategories() {
        try {
            return $this->serviceEntity->getAllCategories();
        } catch (Exception $e) {
            echo "Error getting categories: " . $e->getMessage();
            return [];
        }
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        try {
            return $this->shortlistEntity->getShortlistedCleanerIds($homeownerId);
        } catch (Exception $e) {
            echo "Error getting shortlisted cleaners: " . $e->getMessage();
            return [];
        }
    }
}