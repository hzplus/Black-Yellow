<?php
// Controller/homeowner/CleanerProfileController.php
require_once __DIR__ . '/../../Entity/homeowner/CleanerProfileEntity.php';

class CleanerProfileController {
    private $entity;
    
    public function __construct() {
        $this->entity = new CleanerProfileEntity();
    }
    
    /**
     * Get a cleaner by ID with their services
     * 
     * @param int $cleanerId Cleaner ID
     * @return array|null Cleaner data with services or null if not found
     */
    public function getCleanerById(int $cleanerId): ?array {
        try {
            $cleaner = $this->entity->getCleanerById($cleanerId);
            
            if (!$cleaner) {
                return null;
            }
            
            // Add services to cleaner data
            $cleaner['services'] = $this->entity->getCleanerServices($cleanerId);
            
            // Add default bio and image path for compatibility
            $cleaner['bio'] = "Professional cleaning service provider.";
            $cleaner['profile_image'] = "default.jpg";
            
            return $cleaner;
        } catch (Exception $e) {
            error_log("Error getting cleaner by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get services offered by a cleaner
     * 
     * @param int $cleanerId Cleaner ID
     * @return array Array of service arrays
     */
    public function getCleanerServices(int $cleanerId): array {
        try {
            return $this->entity->getCleanerServices($cleanerId);
        } catch (Exception $e) {
            error_log("Error getting cleaner services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if cleaner is shortlisted by homeowner
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Whether cleaner is shortlisted
     */
    public function isShortlisted(int $cleanerId, int $homeownerId): bool {
        try {
            return $this->entity->isShortlisted($cleanerId, $homeownerId);
        } catch (Exception $e) {
            error_log("Error checking if shortlisted: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle a cleaner's shortlist status
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Success status
     */
    public function toggleShortlist(int $cleanerId, int $homeownerId): bool {
        try {
            if ($this->entity->isShortlisted($cleanerId, $homeownerId)) {
                return $this->entity->removeFromShortlist($homeownerId, $cleanerId);
            } else {
                return $this->entity->addToShortlist($homeownerId, $cleanerId);
            }
        } catch (Exception $e) {
            error_log("Error toggling shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Format service price for display
     * 
     * @param float $price Raw price value
     * @return string Formatted price string
     */
    public function formatPrice(float $price): string {
        return '$' . number_format($price, 2);
    }
}