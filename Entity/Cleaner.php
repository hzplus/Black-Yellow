<?php
require_once 'User.php';

class Cleaner extends User {
    private $bio;
    private $rating;
    private $availability;
    private $services;
    
    /**
     * Constructor for Cleaner class
     * 
     * @param int $userid User ID
     * @param string $username Username
     * @param string $password Password hash
     * @param string $name Full name
     * @param string $email Email address
     * @param string $phone Phone number
     * @param string $address Address
     * @param string $status Account status (Active, Suspended)
     * @param string $bio Cleaner bio/description
     * @param float $rating Average rating
     * @param string $availability Availability schedule
     * @param array $services Services offered
     */
    public function __construct($userid = null, $username = null, $password = null, $name = null, 
                               $email = null, $phone = null, $address = null, $status = 'Active',
                               $bio = null, $rating = 0, $availability = null, $services = []) {
        parent::__construct($userid, $username, $password, 'Cleaner', $name, $email, $phone, $address, $status);
        $this->bio = $bio;
        $this->rating = $rating;
        $this->availability = $availability;
        $this->services = $services;
    }
    
    // Getters
    public function getBio() {
        return $this->bio;
    }
    
    public function getRating() {
        return $this->rating;
    }
    
    public function getAvailability() {
        return $this->availability;
    }
    
    public function getServices() {
        return $this->services;
    }
    
    // Setters
    public function setBio($bio) {
        $this->bio = $bio;
    }
    
    public function setRating($rating) {
        $this->rating = $rating;
    }
    
    public function setAvailability($availability) {
        $this->availability = $availability;
    }
    
    public function setServices($services) {
        $this->services = $services;
    }
    
    // Methods
    public function addService($service) {
        $this->services[] = $service;
    }
    
    public function removeService($serviceId) {
        foreach ($this->services as $key => $service) {
            if ($service['id'] == $serviceId) {
                unset($this->services[$key]);
                return true;
            }
        }
        return false;
    }
}
?>