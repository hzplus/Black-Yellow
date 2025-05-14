<?php
// Controller/homeowner/ServiceHistoryController.php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class ServiceHistoryController {
    private $platformEntity;
    
    public function __construct() {
        $this->platformEntity = new CleaningPlatformEntity();
    }
    
    public function getServiceHistory($homeownerId, $startDate = '', $endDate = '', $cleanerName = '', $category = '') {
        try {
            return $this->platformEntity->getServiceHistory($homeownerId, $startDate, $endDate, $cleanerName, $category);
        } catch (Exception $e) {
            error_log("Error getting service history: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllCategories() {
        try {
            return $this->platformEntity->getAllCategories();
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}
?>