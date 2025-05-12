<?php
// Controller/homeowner/CleanerProfileController.php
require_once(__DIR__ . '/../../Entity/homeowner/CleanerEntity.php');
require_once(__DIR__ . '/../../Entity/homeowner/ServiceEntity.php');

class CleanerProfileController {
    private $cleanerEntity;
    private $serviceEntity;
    
    public function __construct() {
        $this->cleanerEntity = new Cleaner(); // Using the correct class name
        $this->serviceEntity = new CleanerService(null, null, null, null, null, null); // Using the correct class name with required parameters
    }
    
    public function getCleanerById($cleanerId) {
        return $this->cleanerEntity->getCleanerById($cleanerId);
    }
    
    public function getCleanerServices($cleanerId) {
        // Check if the method exists in your CleanerService class
        return $this->serviceEntity->getServicesByCleanerFormatted($cleanerId);
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        return $this->cleanerEntity->isShortlisted($cleanerId, $homeownerId);
    }
    
    public function toggleShortlist($cleanerId, $homeownerId) {
        if ($this->isShortlisted($cleanerId, $homeownerId)) {
            return $this->cleanerEntity->removeFromShortlist($cleanerId, $homeownerId);
        } else {
            return $this->cleanerEntity->addToShortlist($cleanerId, $homeownerId);
        }
    }
}
?>