<?php
class Cleaner {
    private $id;
    private $name;
    private $rating;
    private $price;
    private $availability;
    private $services;
    private $profilePicture;

public function __construct($id, $name, $rating, $price, $availability, $services, $profilePicture = null) {
    $this->id = $id;
    $this->name = $name;
    $this->rating = $rating;
    $this->price = $price;
    $this->availability = $availability;
    $this->services = $services;
    $this->profilePicture = $profilePicture;
}

    // Getter methods for the properties
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getRating() { return $this->rating; }
    public function getPrice() { return $this->price; }
    public function getAvailability() { return $this->availability; }
    public function getServices() { return $this->services; }
}
?>