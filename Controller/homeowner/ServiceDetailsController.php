<?php
// Controller/homeowner/ServiceDetailsController.php
class ServiceDetailsController {
    private $entity;
    
    public function __construct() {
        $this->entity = new CleaningPlatformEntity();
    }
    
    public function getServiceById($serviceId) {
        $service = $this->entity->getServiceDetailById($serviceId);
        return $service;
    }
    
    public function getCleanerById($cleanerId) {
        $cleaner = $this->entity->getCleanerById($cleanerId);
        return $cleaner;
    }
    
    public function isCleanerShortlisted($cleanerId, $homeownerId) {
        return $this->entity->isShortlisted($cleanerId, $homeownerId);
    }
    
    public function incrementViewCount($serviceId) {
        return $this->entity->incrementServiceViewCount($serviceId);
    }
    
    public function toggleShortlist($cleanerId, $homeownerId) {
        if ($this->entity->isShortlisted($cleanerId, $homeownerId)) {
            return $this->entity->removeFromShortlist($homeownerId, $cleanerId);
        } else {
            return $this->entity->addToShortlist($homeownerId, $cleanerId);
        }
    }
}