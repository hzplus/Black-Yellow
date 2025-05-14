<?php
// Controller/homeowner/ServiceDetailsController.php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class ServiceDetailsController {
    private $entity;
    
    public function __construct() {
        $this->entity = new CleaningPlatformEntity();
    }
    
    public function getServiceById($serviceId) {
        // echo "Debug (ServiceDetailsController): Getting service with ID: " . $serviceId . "<br>";
        $service = $this->entity->getServiceDetailById($serviceId);
        // echo "Debug (ServiceDetailsController): Service result: ";
        // var_dump($service);
        // echo "<br>";
        return $service;
    }
    
    public function getCleanerById($cleanerId) {
        // echo "Debug (ServiceDetailsController): Getting cleaner with ID: " . $cleanerId . "<br>";
        $cleaner = $this->entity->getCleanerById($cleanerId);
        // echo "Debug (ServiceDetailsController): Cleaner result: ";
        // var_dump($cleaner);
        // echo "<br>";
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
    
    // public function bookService($homeownerId, $cleanerId, $serviceId, $bookingDateTime, $notes) {
    //     return $this->entity->createBooking($homeownerId, $cleanerId, $serviceId, $bookingDateTime, $notes);
    // }
}