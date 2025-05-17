<?php
require_once(__DIR__ . '/../../Entity/homeowner/Cleaner.php');
require_once(__DIR__ . '/../../db/Database.php');
require_once(__DIR__ . '/../../Entity/homeowner/Service.php');

class CleanerListingController {
    
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Fetch all cleaners along with their services
    public function getCleaners() {
        $queryCleaners = "SELECT userid, username, email, status FROM users WHERE role = 'cleaner'";
        $resultCleaners = $this->db->query($queryCleaners);

        $cleaners = [];

        if ($resultCleaners && $resultCleaners->num_rows > 0) {
            // Prepare the service query once
            $queryServices = "SELECT serviceid, title, description, price, availability FROM services WHERE cleanerid = ?";
            $stmtServices = $this->db->prepare($queryServices);

            while ($row = $resultCleaners->fetch_assoc()) {
                $cleanerId = $row['userid'];

                $cleaner = new Cleaner();
                $cleaner->setId($row['userid']);
                $cleaner->setName($row['username']);
                $cleaner->setEmail($row['email']);
                $cleaner->setStatus($row['status']);
                $cleaner->setServices([]);

                // Bind and execute service query
                $stmtServices->bind_param("i", $cleanerId);
                $stmtServices->execute();
                $resultServices = $stmtServices->get_result();

                while ($serviceRow = $resultServices->fetch_assoc()) {
                    $service = new Service();
                    $service->setId($serviceRow['serviceid']);
                    $service->setTitle($serviceRow['title']);
                    $service->setDescription($serviceRow['description']);
                    $service->setPrice($serviceRow['price']);
                    $service->setAvailability($serviceRow['availability']);

                    $cleaner->addService($service);
                }

                $cleaners[] = $cleaner;
            }

            $stmtServices->close();
        }

        return $cleaners;
    }

    // Function to search cleaners by name
    public function searchCleanersByName($searchTerm) {
        $query = "
            SELECT u.userid, u.username, u.email, u.status
            FROM users u
            WHERE u.role = 'cleaner' AND u.username LIKE ?
        ";
        
        $searchParam = "%" . $searchTerm . "%";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cleaners = [];
        
        while ($row = $result->fetch_assoc()) {
            $cleaner = new Cleaner();
            $cleaner->setId($row['userid']);
            $cleaner->setName($row['username']);
            $cleaner->setEmail($row['email']);
            $cleaner->setStatus($row['status']);
            
            // Get services for this cleaner
            $serviceQuery = "SELECT serviceid, title, description, price, availability FROM services WHERE cleanerid = ?";
            $serviceStmt = $this->db->prepare($serviceQuery);
            $serviceStmt->bind_param("i", $row['userid']);
            $serviceStmt->execute();
            $serviceResult = $serviceStmt->get_result();
            
            $services = [];
            while ($serviceRow = $serviceResult->fetch_assoc()) {
                $service = new Service();
                $service->setId($serviceRow['serviceid']);
                $service->setTitle($serviceRow['title']);
                $service->setDescription($serviceRow['description']);
                $service->setPrice($serviceRow['price']);
                $service->setAvailability($serviceRow['availability']);
                $cleaner->addService($service);
            }
            
            $cleaners[] = $cleaner;
            $serviceStmt->close();
        }
        
        $stmt->close();
        return $cleaners;
    }
    
    // Function to search cleaners by service
    public function searchCleanersByService($searchTerm) {
        $query = "
            SELECT DISTINCT u.userid, u.username, u.email, u.status
            FROM users u
            JOIN services s ON u.userid = s.cleanerid
            WHERE u.role = 'cleaner' AND s.title LIKE ?
        ";
        
        $searchParam = "%" . $searchTerm . "%";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cleaners = [];
        
        while ($row = $result->fetch_assoc()) {
            $cleaner = new Cleaner();
            $cleaner->setId($row['userid']);
            $cleaner->setName($row['username']);
            $cleaner->setEmail($row['email']);
            $cleaner->setStatus($row['status']);
            
            // Get services for this cleaner
            $serviceQuery = "SELECT serviceid, title, description, price, availability FROM services WHERE cleanerid = ?";
            $serviceStmt = $this->db->prepare($serviceQuery);
            $serviceStmt->bind_param("i", $row['userid']);
            $serviceStmt->execute();
            $serviceResult = $serviceStmt->get_result();
            
            while ($serviceRow = $serviceResult->fetch_assoc()) {
                $service = new Service();
                $service->setId($serviceRow['serviceid']);
                $service->setTitle($serviceRow['title']);
                $service->setDescription($serviceRow['description']);
                $service->setPrice($serviceRow['price']);
                $service->setAvailability($serviceRow['availability']);
                $cleaner->addService($service);
            }
            
            $cleaners[] = $cleaner;
            $serviceStmt->close();
        }
        
        $stmt->close();
        return $cleaners;
    }

    public function getCleanerById($cleanerId) {
        // Updated to only select columns that exist in the database
        $query = "
            SELECT u.userid, u.username, u.email, u.status
            FROM users u
            WHERE u.userid = ? AND u.role = 'cleaner'
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();
        $cleaner = new Cleaner();
        $cleaner->setId($row['userid']);
        $cleaner->setName($row['username']);
        $cleaner->setEmail($row['email']);
        $cleaner->setStatus($row['status']);
        // Setting default values for bio and profile picture
        $cleaner->setBio('No bio available for this cleaner.');
        $cleaner->setProfilePicture('default.jpg');
        
        // Get services for this cleaner
        $serviceQuery = "SELECT serviceid, title, description, price, availability, category, image_path FROM services WHERE cleanerid = ?";
        $serviceStmt = $this->db->prepare($serviceQuery);
        $serviceStmt->bind_param("i", $cleanerId);
        $serviceStmt->execute();
        $serviceResult = $serviceStmt->get_result();
        
        while ($serviceRow = $serviceResult->fetch_assoc()) {
            $service = new Service();
            $service->setId($serviceRow['serviceid']);
            $service->setTitle($serviceRow['title']);
            $service->setDescription($serviceRow['description']);
            $service->setPrice($serviceRow['price']);
            $service->setAvailability($serviceRow['availability']);
            $service->setCategory($serviceRow['category'] ?? null);
            $service->setImagePath($serviceRow['image_path'] ?? null);
            $cleaner->addService($service);
        }
        
        $serviceStmt->close();
        $stmt->close();
        
        return $cleaner;
    }
}
?>