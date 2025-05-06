<?php
require_once 'db.php';
require_once __DIR__ . '/../Entity/ServiceHistory.php';
require_once 'CleanerDAO.php';

class HistoryDAO {
    private $conn;
    private $cleanerDAO;
    
    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
        $this->cleanerDAO = new CleanerDAO();
    }
    
    // Get service history for a homeowner
    public function getServiceHistory($homeownerId, $fromDate = null, $toDate = null) {
        $query = "
            SELECT * FROM service_history 
            WHERE homeowner_id = ?
        ";
        
        $params = [$homeownerId];
        $types = "i";
        
        if ($fromDate && $toDate) {
            $query .= " AND service_date BETWEEN ? AND ?";
            $params[] = $fromDate;
            $params[] = $toDate;
            $types .= "ss";
        } else if ($fromDate) {
            $query .= " AND service_date >= ?";
            $params[] = $fromDate;
            $types .= "s";
        } else if ($toDate) {
            $query .= " AND service_date <= ?";
            $params[] = $toDate;
            $types .= "s";
        }
        
        $query .= " ORDER BY service_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $history = [];
        while ($row = $result->fetch_assoc()) {
            $serviceHistory = new ServiceHistory();
            $serviceHistory->id = $row['id'];
            $serviceHistory->homeownerId = $row['homeowner_id'];
            $serviceHistory->cleanerId = $row['cleaner_id'];
            $serviceHistory->serviceDate = $row['service_date'];
            $serviceHistory->serviceName = $row['service_name'];
            $serviceHistory->price = $row['price'];
            $serviceHistory->summary = $row['summary'];
            
            // Get cleaner details
            $serviceHistory->cleaner = $this->cleanerDAO->getCleanerById($row['cleaner_id']);
            
            $history[] = $serviceHistory;
        }
        
        return $history;
    }
    
    // Add new service history record
    public function addServiceHistory($serviceHistory) {
        $stmt = $this->conn->prepare("
            INSERT INTO service_history 
            (homeowner_id, cleaner_id, service_date, service_name, price, summary) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "iissds", 
            $serviceHistory->homeownerId,
            $serviceHistory->cleanerId,
            $serviceHistory->serviceDate,
            $serviceHistory->serviceName,
            $serviceHistory->price,
            $serviceHistory->summary
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
}
?>