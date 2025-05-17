<?php
// Controller/homeowner/ServiceDetailsController.php
require_once __DIR__ . '/../../Entity/homeownerServiceDetailsEntity.php';

class ServiceDetailsController {
    private $entity;
    
    public function __construct() {
        $this->entity = new ServiceDetailsEntity();
    }
    
    /**
     * Get service details by ID
     * 
     * @param int $serviceId Service ID
     * @return array|null Service data or null if not found
     */
    public function getServiceById(int $serviceId): ?array {
        try {
            $service = $this->entity->getServiceById($serviceId);
            
            if (!$service) {
                return null;
            }
            
            // Add calculated fields for convenience
            $service['formatted_price'] = '$' . number_format($service['price'], 2);
            
            return $service;
        } catch (Exception $e) {
            error_log("Error getting service by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get cleaner information by ID
     * 
     * @param int $cleanerId Cleaner ID
     * @return array|null Cleaner data or null if not found
     */
    public function getCleanerById(int $cleanerId): ?array {
        try {
            $cleaner = $this->entity->getCleanerById($cleanerId);
            
            if (!$cleaner) {
                return null;
            }
            
            // Add default profile image
            $cleaner['profile_image'] = 'default.jpg';
            
            return $cleaner;
        } catch (Exception $e) {
            error_log("Error getting cleaner by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Increment view count for a service
     * 
     * @param int $serviceId Service ID
     * @return bool Success status
     */
    public function incrementViewCount(int $serviceId): bool {
        try {
            return $this->entity->incrementViewCount($serviceId);
        } catch (Exception $e) {
            error_log("Error incrementing view count: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if cleaner is shortlisted by homeowner
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Whether cleaner is shortlisted
     */
    public function isCleanerShortlisted(int $cleanerId, int $homeownerId): bool {
        try {
            return $this->entity->isCleanerShortlisted($cleanerId, $homeownerId);
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
            return $this->entity->toggleShortlist($cleanerId, $homeownerId);
        } catch (Exception $e) {
            error_log("Error toggling shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Book a service
     * 
     * @param int $serviceId Service ID to book
     * @param int $cleanerId Cleaner offering the service
     * @param int $homeownerId Homeowner booking the service
     * @param string $bookingDate Requested booking date
     * @param string $notes Optional booking notes
     * @return int|false Booking ID on success, false on failure
     */
    public function bookService(int $serviceId, int $cleanerId, int $homeownerId, string $bookingDate, string $notes = ''): int|false {
        try {
            return $this->entity->bookService($serviceId, $cleanerId, $homeownerId, $bookingDate, $notes);
        } catch (Exception $e) {
            error_log("Error booking service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Format price for display
     * 
     * @param float $price Raw price value
     * @return string Formatted price string
     */
    public function formatPrice(float $price): string {
        return '$' . number_format($price, 2);
    }
}   