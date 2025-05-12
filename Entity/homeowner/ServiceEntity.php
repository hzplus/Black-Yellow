<?php
require_once __DIR__ . '/../../db/Database.php';

class CleanerService {

    public $serviceId;
    public $cleanerId;
    public $serviceType;
    public $price;
    public $description;
    public $availability;
    public $category;
    public $image_path;
    
    public function __construct($serviceId, $cleanerId, $serviceType, $price, $description, $availability, $category = null, $image_path = null) {
        $this->serviceId = $serviceId;
        $this->cleanerId = $cleanerId;
        $this->serviceType = $serviceType;
        $this->price = $price;
        $this->description = $description;
        $this->availability = $availability;
        $this->category = $category;
        $this->image_path = $image_path;
    }
    
    // Getters for ViewCleanerProfile.php
    public function getId() {
        return $this->serviceId;
    }
    
    public function getTitle() {
        return $this->serviceType;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getFormattedPrice() {
        return '$' . number_format($this->price, 2);
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    // Existing database methods
    function getServicesByCleaner($cleanerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ?");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $services;
    }
    
    // New method to get formatted service objects for view
    function getServicesByCleanerFormatted($cleanerId) {
        $rawServices = $this->getServicesByCleaner($cleanerId);
        $formattedServices = [];
        
        foreach ($rawServices as $service) {
            $formattedServices[] = new CleanerService(
                $service['serviceid'],
                $service['cleanerid'],
                $service['title'],
                $service['price'],
                $service['description'],
                $service['availability'],
                $service['category'],
                $service['image_path']
            );
        }
        
        return $formattedServices;
    }
    
    function createService($cleanerId, $title, $description, $price, $availability, $category, $image_path) {
        // Existing implementation
    }
    
    function searchServicesByTitle($cleanerid, $keyword) {
        // Existing implementation
    }
    
    function getServiceById($serviceid) {
        // Existing implementation
    }
    
    function updateService($serviceid, $title, $description, $price, $availability, $category, $image_path) {
        // Existing implementation
    }
    
    function deleteService($serviceid) {
        // Existing implementation
    }
    
    function getAllCategories() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT name FROM service_categories");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['name'];
        }
        
        return $categories;
    }
}
?>