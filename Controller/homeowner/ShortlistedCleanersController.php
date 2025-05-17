<?php
// Controller/homeowner/ShortlistedCleanersController.php
require_once __DIR__ . '/../../Entity/homeowner/ShortlistedCleanersEntity.php';

class ShortlistedCleanersController {
    private $entity;
    
    public function __construct() {
        $this->entity = new ShortlistedCleanersEntity();
    }
    
    /**
     * Get all shortlisted cleaners for a homeowner with their services
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of cleaner objects with their services
     */
    public function getShortlistedCleaners(int $homeownerId): array {
        try {
            $cleaners = $this->entity->getShortlistedCleaners($homeownerId);
            return $this->enrichCleanersWithServices($cleaners);
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search shortlisted cleaners by name or category
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $search Search term
     * @param string $category Optional category filter
     * @return array Array of matching cleaner objects with their services
     */
    public function searchShortlisted(int $homeownerId, string $search = '', string $category = ''): array {
        try {
            $cleaners = $this->entity->searchShortlisted($homeownerId, $search, $category);
            return $this->enrichCleanersWithServices($cleaners);
        } catch (Exception $e) {
            error_log("Error searching shortlisted cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Remove a cleaner from homeowner's shortlist
     * 
     * @param int $homeownerId Homeowner ID
     * @param int $cleanerId Cleaner ID to remove
     * @return bool Success status
     */
    public function removeFromShortlist(int $homeownerId, int $cleanerId): bool {
        try {
            return $this->entity->removeFromShortlist($homeownerId, $cleanerId);
        } catch (Exception $e) {
            error_log("Error removing from shortlist: " . $e->getMessage());
            return false;
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