<?php
require_once __DIR__ . '/../db/Database.php';

class CleanerService {

    public $serviceId;
    public $cleanerId;
    public $serviceType;
    public $price;
    public $description;
    public $availability;

    public function __construct($serviceId, $cleanerId, $serviceType, $price, $description, $availability) {
        $this->serviceId = $serviceId;
        $this->cleanerId = $cleanerId;
        $this->serviceType = $serviceType;
        $this->price = $price;
        $this->description = $description;
        $this->availability = $availability;
    }

    public static function getServicesByCleaner($cleanerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ?");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $services;
    }

    public static function createService($cleanerId, $title, $description, $price, $availability, $category, $image_path) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO services (cleanerid, title, description, price, availability, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdsss", $cleanerId, $title, $description, $price, $availability, $category, $image_path);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    function getAllCategories() {
        $conn = Database::getConnection();
        $query = "SELECT name FROM service_categories";
        $result = $conn->query($query);
        
        $categories = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['name'];
            }
        }
        return $categories;
    }
    
    

    public static function searchServicesByTitle($cleanerid, $keyword) {
        $conn = Database::getConnection();
        $keyword = "%" . $keyword . "%";
        $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ? AND (title LIKE ? OR category LIKE ?)");
        $stmt->bind_param("iss", $cleanerid, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $services = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $services;
    }

    public static function getServiceById($serviceid) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM services WHERE serviceid = ?");
        $stmt->bind_param("i", $serviceid);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $stmt->close();
        return $service;
    }
    

     function updateService($serviceid, $title, $description, $price, $availability, $category, $image_path) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE services SET title = ?, description = ?, price = ?, availability = ?, category = ?, image_path = ? WHERE serviceid = ?");
        $stmt->bind_param("ssdsssi", $title, $description, $price, $availability, $category, $image_path, $serviceid);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public static function deleteService($serviceid) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM services WHERE serviceid = ?");
        $stmt->bind_param("i", $serviceid);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public static function getServiceViews($serviceId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT view_count FROM services WHERE serviceid = ?");
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['view_count'] : 0;
    }
    
    public function getServiceShortlists($serviceid) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT shortlist_count FROM services WHERE serviceid = ?");
        $stmt->bind_param("i", $serviceid);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ? $data['shortlist_count'] : 0;
    }
    
}
?>
