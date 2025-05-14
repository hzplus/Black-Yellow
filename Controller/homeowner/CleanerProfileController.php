<?php
// Controller/homeowner/CleanerProfileController.php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class CleanerProfileController {
    private $platformEntity;
    
    public function __construct() {
        $this->platformEntity = new CleaningPlatformEntity();
    }
    
    public function getCleanerById($cleanerId) {
        return $this->platformEntity->getCleanerById($cleanerId);
    }
    
    public function getCleanerServices($cleanerId) {
        return $this->platformEntity->getServicesByCleanerFormatted($cleanerId);
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        return $this->platformEntity->isShortlisted($cleanerId, $homeownerId);
    }
    
    public function toggleShortlist($cleanerId, $homeownerId) {
        if ($this->isShortlisted($cleanerId, $homeownerId)) {
            return $this->platformEntity->removeFromShortlist($homeownerId, $cleanerId);
        } else {
            return $this->platformEntity->addToShortlist($homeownerId, $cleanerId);
        }
    }
}
?>