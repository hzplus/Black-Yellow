<?php
// Controller/homeowner/ServiceHistoryController.php
require_once __DIR__ . '/../../Entity/homeowner/ServiceHistoryEntity.php';

class ServiceHistoryController {
    private $entity;
    
    public function __construct() {
        $this->entity = new ServiceHistoryEntity();
    }
    
    /**
     * Get service history for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $startDate Optional start date filter (Y-m-d)
     * @param string $endDate Optional end date filter (Y-m-d)
     * @param string $cleanerName Optional cleaner name filter
     * @param string $category Optional service category filter
     * @return array Array of service history records
     */
    public function getServiceHistory(
        int $homeownerId, 
        string $startDate = '', 
        string $endDate = '', 
        string $cleanerName = '', 
        string $category = ''
    ): array {
        try {
            $history = $this->entity->getServiceHistory(
                $homeownerId, 
                $startDate, 
                $endDate, 
                $cleanerName, 
                $category
            );
            
            // Enhance data with formatted values
            foreach ($history as &$record) {
                $record['formatted_date'] = date('F j, Y', strtotime($record['service_date']));
                $record['formatted_price'] = '$' . number_format($record['price'], 2);
            }
            
            return $history;
        } catch (Exception $e) {
            error_log("Error getting service history: " . $e->getMessage());
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
     * Get all cleaners the homeowner has worked with
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of cleaners
     */
    public function getPreviousCleaners(int $homeownerId): array {
        try {
            return $this->entity->getPreviousCleaners($homeownerId);
        } catch (Exception $e) {
            error_log("Error getting previous cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all services a homeowner has received from a specific cleaner
     * 
     * @param int $homeownerId Homeowner ID
     * @param int $cleanerId Cleaner ID
     * @return array Array of services
     */
    public function getServicesFromCleaner(int $homeownerId, int $cleanerId): array {
        try {
            $services = $this->entity->getServicesFromCleaner($homeownerId, $cleanerId);
            
            // Enhance data with formatted values
            foreach ($services as &$service) {
                $service['formatted_date'] = date('F j, Y', strtotime($service['booking_date']));
                $service['formatted_price'] = '$' . number_format($service['price'], 2);
            }
            
            return $services;
        } catch (Exception $e) {
            error_log("Error getting services from cleaner: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Format date for display
     * 
     * @param string $dateString Date string
     * @return string Formatted date
     */
    public function formatDate(string $dateString): string {
        return date('F j, Y', strtotime($dateString));
    }
}