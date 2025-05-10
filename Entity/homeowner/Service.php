<?php
// Fix the path to Database.php
require_once __DIR__ . '/../../db/Database.php';

class Service {
    private $id;
    private $title;
    private $description;
    private $price;
    private $availability;
    private $category;
    private $imagePath;
    private $viewCount;
    private $shortlistCount;
    private $cleanerId;
    private $cleanerName;
    private $cleanerEmail;
    private $cleanerProfilePicture;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function getAvailability() {
        return $this->availability;
    }

    public function setAvailability($availability) {
        $this->availability = $availability;
        return $this;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }
    
    public function getImagePath() {
        return $this->imagePath;
    }
    
    public function setImagePath($imagePath) {
        $this->imagePath = $imagePath;
        return $this;
    }
    
    public function getViewCount() {
        return $this->viewCount;
    }
    
    public function setViewCount($viewCount) {
        $this->viewCount = $viewCount;
        return $this;
    }
    
    public function getShortlistCount() {
        return $this->shortlistCount;
    }
    
    public function setShortlistCount($shortlistCount) {
        $this->shortlistCount = $shortlistCount;
        return $this;
    }
    
    public function getCleanerId() {
        return $this->cleanerId;
    }
    
    public function setCleanerId($cleanerId) {
        $this->cleanerId = $cleanerId;
        return $this;
    }
    
    public function getCleanerName() {
        return $this->cleanerName;
    }
    
    public function setCleanerName($cleanerName) {
        $this->cleanerName = $cleanerName;
        return $this;
    }
    
    public function getCleanerEmail() {
        return $this->cleanerEmail;
    }
    
    public function setCleanerEmail($cleanerEmail) {
        $this->cleanerEmail = $cleanerEmail;
        return $this;
    }
    
    public function getCleanerProfilePicture() {
        return $this->cleanerProfilePicture;
    }
    
    public function setCleanerProfilePicture($cleanerProfilePicture) {
        $this->cleanerProfilePicture = $cleanerProfilePicture;
        return $this;
    }
    
    // Format price as currency string
    public function getFormattedPrice() {
        return "$" . number_format($this->price, 2);
    }
    
    // Get formatted availability date range
    public function getFormattedAvailability() {
        // This method assumes availability is stored in a standard format
        return $this->availability;
    }
}
?>