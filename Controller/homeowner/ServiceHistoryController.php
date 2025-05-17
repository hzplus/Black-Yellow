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
     * Get pending bookings for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of pending booking records
     */
    public function getPendingBookings(int $homeownerId): array {
        try {
            $bookings = $this->entity->getPendingBookings($homeownerId);
            
            // Enhance data with formatted values
            foreach ($bookings as &$booking) {
                $booking['formatted_date'] = date('F j, Y', strtotime($booking['booking_date']));
                $booking['formatted_price'] = '$' . number_format($booking['price'], 2);
            }
            
            return $bookings;
        } catch (Exception $e) {
            error_log("Error getting pending bookings: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cancel a booking
     * 
     * @param int $bookingId Booking ID to cancel
     * @param int $homeownerId Homeowner ID for verification
     * @return bool Success status
     */
    public function cancelBooking(int $bookingId, int $homeownerId): bool {
        try {
            return $this->entity->cancelBooking($bookingId, $homeownerId);
        } catch (Exception $e) {
            error_log("Error canceling booking: " . $e->getMessage());
            return false;
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