<?php
class Shortlist {
    private $homeownerId;
    private $cleanerId;
    private $dateAdded;
    
    /**
     * Constructor for Shortlist class
     * 
     * @param int $homeownerId Homeowner user ID
     * @param int $cleanerId Cleaner user ID
     * @param string $dateAdded Date when cleaner was added to shortlist
     */
    public function __construct($homeownerId = null, $cleanerId = null, $dateAdded = null) {
        $this->homeownerId = $homeownerId;
        $this->cleanerId = $cleanerId;
        $this->dateAdded = $dateAdded ? $dateAdded : date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getHomeownerId() {
        return $this->homeownerId;
    }
    
    public function getCleanerId() {
        return $this->cleanerId;
    }
    
    public function getDateAdded() {
        return $this->dateAdded;
    }
    
    // Setters
    public function setHomeownerId($homeownerId) {
        $this->homeownerId = $homeownerId;
    }
    
    public function setCleanerId($cleanerId) {
        $this->cleanerId = $cleanerId;
    }
    
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
    }
}
?>