<?php
require_once 'Controller/HomeownerController.php';

class HomeownerBoundary {

    private $controller;

    public function __construct() {
        $this->controller = new HomeownerController();
    }

    public function showFindCleaner() {
        $cleaners = $this->controller->getCleaners();

        foreach ($cleaners as $cleaner) {
            // Here you can decide how to present each cleaner
            echo "Cleaner Name: " . $cleaner->getName();
            echo "Price: " . $cleaner->getPrice();
            echo "Rating: " . $cleaner->getRating();
            echo "Availability: " . $cleaner->getAvailability();
            echo "<br>";
        }
    }

    public function showViewProfile($cleanerId) {
        $cleaner = $this->controller->getCleanerById($cleanerId);
        echo "Cleaner Name: " . $cleaner->getName();
        echo "Price: " . $cleaner->getPrice();
        echo "Rating: " . $cleaner->getRating();
        echo "Availability: " . $cleaner->getAvailability();
        echo "<br>";

        // Display services offered
        $services = $cleaner->getServices();
        foreach ($services as $service) {
            echo "Service: " . $service['name'] . " | Price: " . $service['price'] . "<br>";
        }
    }

    public function showShortlistedCleaners() {
        // Fetch and display shortlisted cleaners
        echo "Shortlisted Cleaners: <br>";

        // Just an example, you would fetch real data from the controller
        $shortlistedCleaners = $this->controller->getCleaners(); 

        foreach ($shortlistedCleaners as $cleaner) {
            echo "Cleaner Name: " . $cleaner->getName() . "<br>";
        }
    }

    public function showServiceHistory($homeownerId) {
        $history = $this->controller->getServiceHistory($homeownerId);

        foreach ($history as $service) {
            echo "Service Date: " . $service['date'] . " | Price Paid: " . $service['price'] . "<br>";
        }
    }

    public function showMyAccount($homeownerId) {
        echo "Edit Profile Form: (Placeholder for profile editing form)";
        // Here you would render a form to edit the homeowner's profile
    }
}