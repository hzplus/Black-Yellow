<?php
// Controller/homeowner/ServiceHistoryController.php
require_once(__DIR__ . '/../../Entity/homeowner/ServiceEntity.php');
require_once(__DIR__ . '/../../db/Database.php');

class ServiceHistory {
    private $serviceId;
    private $cleanerId;
    private $cleanerName;
    private $serviceTitle;
    private $category;
    private $price;
    private $serviceDate;
    private $notes;
    
    public function __construct($data) {
        $this->serviceId = $data['serviceid'] ?? null;
        $this->cleanerId = $data['cleanerid'] ?? null;
        $this->cleanerName = $data['cleaner_name'] ?? null;
        $this->serviceTitle = $data['service_title'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->price = $data['price'] ?? 0;
        $this->serviceDate = $data['service_date'] ?? null;
        $this->notes = $data['notes'] ?? null;
    }
    
    public function getServiceId() {
        return $this->serviceId;
    }
    
    public function getCleanerId() {
        return $this->cleanerId;
    }
    
    public function getCleanerName() {
        return $this->cleanerName;
    }
    
    public function getServiceTitle() {
        return $this->serviceTitle;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getFormattedPrice() {
        return '$' . number_format($this->price, 2);
    }
    
    public function getFormattedDate() {
        $date = new DateTime($this->serviceDate);
        return $date->format('F j, Y');
    }
    
    public function getNotes() {
        return $this->notes;
    }
}

class ServiceHistoryController {
    private $serviceEntity;
    
    public function __construct() {
        $this->serviceEntity = new CleanerService(null, null, null, null, null, null);
    }
    
    public function getServiceHistory($homeownerId, $startDate = '', $endDate = '', $cleanerName = '', $category = '') {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT b.bookingid, b.homeownerid, b.cleanerid, b.serviceid, b.booking_date as service_date, 
                          b.notes, s.title as service_title, s.price, s.category, u.username as cleaner_name
                   FROM bookings b
                   JOIN services s ON b.serviceid = s.serviceid
                   JOIN users u ON b.cleanerid = u.userid
                   WHERE b.homeownerid = ? AND b.status = 'completed'";
            
            $params = [$homeownerId];
            $types = "i";
            
            if (!empty($startDate)) {
                $sql .= " AND b.booking_date >= ?";
                $params[] = $startDate;
                $types .= "s";
            }
            
            if (!empty($endDate)) {
                $sql .= " AND b.booking_date <= ?";
                $params[] = $endDate . " 23:59:59"; // End of day
                $types .= "s";
            }
            
            if (!empty($cleanerName)) {
                $sql .= " AND u.username LIKE ?";
                $params[] = "%$cleanerName%";
                $types .= "s";
            }
            
            if (!empty($category)) {
                $sql .= " AND s.category = ?";
                $params[] = $category;
                $types .= "s";
            }
            
            $sql .= " ORDER BY b.booking_date DESC";
            
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $serviceHistory = [];
            while ($row = $result->fetch_assoc()) {
                $serviceHistory[] = new ServiceHistory($row);
            }
            
            $stmt->close();
            return $serviceHistory;
            
        } catch (Exception $e) {
            error_log("Error getting service history: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllCategories() {
        try {
            return $this->serviceEntity->getAllCategories();
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}
?>