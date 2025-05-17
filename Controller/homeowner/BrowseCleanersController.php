<?php
// Controller/homeowner/BrowseCleanersController.php
require_once __DIR__ . '/../../Entity/homeowner/BrowseCleanersEntity.php';

class BrowseCleanersController {
    private $entity;
    
    public function __construct() {
        $this->entity = new BrowseCleanersEntity();
    }
    
    /**
     * Get all active cleaners with their services
     * 
     * @param string $sortBy Column to sort by ('name' or 'price')
     * @return array Array of cleaners with their services
     */
    public function getAllCleaners(string $sortBy = 'name'): array {
        try {
            $cleaners = $this->entity->getAllCleaners($sortBy);
            return $this->enrichCleanersWithServices($cleaners);
        } catch (Exception $e) {
            error_log("Error getting cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search for cleaners by name or category
     * 
     * @param string $search Search term
     * @param string $category Optional category filter
     * @param string $sortBy Column to sort by ('name' or 'price')
     * @return array Array of matching cleaners with their services
     */
    public function searchCleaners(string $search, string $category = '', string $sortBy = 'name'): array {
        try {
            $cleaners = $this->entity->searchCleaners($search, $category, $sortBy);
            return $this->enrichCleanersWithServices($cleaners);
        } catch (Exception $e) {
            error_log("Error searching cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get shortlisted cleaner IDs for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of shortlisted cleaner IDs
     */
    public function getShortlistedCleanerIds(int $homeownerId): array {
        try {
            return $this->entity->getShortlistedCleanerIds($homeownerId);
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaner IDs: " . $e->getMessage());
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
            return $this->entity->getAllCategories();
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Helper method to add services to cleaner objects
     * 
     * @param array $cleaners Array of cleaner objects
     * @return array Enhanced cleaner objects with services
     */
    private function enrichCleanersWithServices(array $cleaners): array {
        foreach ($cleaners as &$cleaner) {
            $cleaner['services'] = $this->entity->getCleanerServices($cleaner['userid']);
        }
        return $cleaners;
    }
}