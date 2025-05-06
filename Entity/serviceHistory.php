<?php
class ServiceHistory {
    private $id;
    private $homeownerId;
    private $cleanerId;
    private $serviceId;
    private $date;
    private $price;
    private $summary;
    private $rating;
    private $review;
    
    /**
     * Constructor for ServiceHistory class
     * 
     * @param int $id Service history ID
     * @param int $homeownerId Homeowner user ID
     * @param int $cleanerId Cleaner user ID
     * @param int $serviceId Service ID
     * @param string $date Date of service
     * @param float $price Price paid
     * @param string $summary Service summary
     * @param int $rating Rating given by homeowner (1-5)
     * @param string $review Review given by homeowner
     */
    public function __construct($id = null, $homeownerId = null, $cleanerId = null, $serviceId = null,
                               $date = null, $price = null, $summary = null, $rating = null, $review = null) {
        $this->id = $id;
        $this->homeownerId = $homeownerId;
        $this->cleanerId = $cleanerId;
        $this->serviceId = $serviceId;
        $this->date = $date;
        $this->price = $price;
        $this->summary = $summary;
        $this->rating = $rating;
        $this->review = $review;
    }
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getHomeownerId() {
        return $this->homeownerId;
    }
    
    public function getCleanerId() {
        return $this->cleanerId;
    }
    
    public function getServiceId() {
        return $this->serviceId;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getSummary() {
        return $this->summary;
    }
    
    public function getRating() {
        return $this->rating;
    }
    
    public function getReview() {
        return $this->review;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setHomeownerId($homeownerId) {
        $this->homeownerId = $homeownerId;
    }
    
    public function setCleanerId($cleanerId) {
        $this->cleanerId = $cleanerId;
    }
    
    public function setServiceId($serviceId) {
        $this->serviceId = $serviceId;
    }
    
    public function setDate($date) {
        $this->date = $date;
    }
    
    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function setSummary($summary) {
        $this->summary = $summary;
    }
    
    public function setRating($rating) {
        $this->rating = $rating;
    }
    
    public function setReview($review) {
        $this->review = $review;
    }
}
?>