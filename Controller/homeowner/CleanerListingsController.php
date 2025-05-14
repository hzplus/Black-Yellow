<?php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class CleanerListingsController {
    private $platformEntity;
    
    public function __construct() {
        $this->platformEntity = new CleaningPlatformEntity();
    }
    
    public function getAllCleaners($sortBy = 'name') {
        try {
            return $this->platformEntity->getAllCleaners($sortBy);
        } catch (Exception $e) {
            echo "Error getting cleaners: " . $e->getMessage();
            return [];
        }
    }
    
    public function searchCleaners($search, $category, $sortBy = 'name') {
        try {
            return $this->platformEntity->searchCleaners($search, $category, $sortBy);
        } catch (Exception $e) {
            echo "Error searching cleaners: " . $e->getMessage();
            return [];
        }
    }
    
    public function getAllCategories() {
        try {
            return $this->platformEntity->getAllCategories();
        } catch (Exception $e) {
            echo "Error getting categories: " . $e->getMessage();
            return [];
        }
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        try {
            return $this->platformEntity->getShortlistedCleanerIds($homeownerId);
        } catch (Exception $e) {
            echo "Error getting shortlisted cleaners: " . $e->getMessage();
            return [];
        }
    }
}