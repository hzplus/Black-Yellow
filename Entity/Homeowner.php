<?php
// Combined entity for the cleaning platform
require_once __DIR__ . '/../db/Database.php';

/**
 * Class for representing a service history item
 */
class ServiceHistory {
    public $serviceId;
    public $cleanerId;
    public $cleanerName;
    public $serviceTitle;
    public $category;
    public $price;
    public $serviceDate;
    public $notes;
    
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

/**
 * Class for representing a service detail
 */
class ServiceDetail {
    public $serviceid;
    public $cleanerid;
    public $title;
    public $description;
    public $price;
    public $category;
    public $view_count;
    public $shortlist_count;
    public $image_path;
    public $availability;

    public function __construct($data) {
        $this->serviceid = $data['serviceid'] ?? null;
        $this->cleanerid = $data['cleanerid'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->view_count = $data['view_count'] ?? 0;
        $this->shortlist_count = $data['shortlist_count'] ?? 0;
        $this->image_path = $data['image_path'] ?? null;
        $this->availability = $data['availability'] ?? null;
    }

    public function getServiceId() {
        return $this->serviceid;
    }

    public function getId() {
        return $this->serviceid;
    }

    public function getCleanerId() {
        return $this->cleanerid;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getFormattedPrice() {
        return '$' . number_format($this->price, 2);
    }

    public function getAvailability() {
        return $this->availability;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getImagePath() {
        return $this->image_path;
    }

    public function getViewCount() {
        return $this->view_count;
    }

    public function getShortlistCount() {
        return $this->shortlist_count;
    }
}

/**
 * Class for representing a homeowner
 */
class Homeowner {
    private $id;
    private $name;
    private $email;
    
    public function __construct($data) {
        $this->id = $data['userid'];
        $this->name = $data['username'];
        $this->email = $data['email'];
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    // These methods are added for compatibility but will return empty strings
    public function getPhone() {
        return '';
    }
    
    public function getAddress() {
        return '';
    }
}

/**
 * Class for representing a cleaner service
 */
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
}

/**
 * Class for representing a cleaner
 */
class Cleaner {
    public $id;
    public $name;
    public $email;
    public $status;
    public $services = [];
    
    public function __construct($id = null, $name = null, $email = null, $status = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    // For compatibility with ViewCleanerProfile.php
    public function getBio() {
        return "Professional cleaning service provider."; // Default bio
    }
    
    public function getPhone() {
        return null; // Not in the database
    }
    
    public function getProfileImage() {
        return "default.jpg"; // Default image
    }
    
    public function getServices() {
        return $this->services;
    }
    
    public function getAvgPrice() {
        // Calculate the average price of all services
        $totalPrice = 0;
        $count = count($this->services);
        
        if ($count === 0) {
            return 0; // No services, return 0
        }
        
        foreach ($this->services as $service) {
            $totalPrice += $service->getPrice();
        }
        
        return $totalPrice / $count;
    }
    
    // Set services method to help with compatibility
    public function setServices($services) {
        $this->services = $services;
    }
}

/**
 * Main combined entity class for the cleaning platform
 */
class CleaningPlatformEntity {
    /**
     * Service-related methods
     */
    
    public function getServiceDetailById($serviceId) {
        try {
            $conn = Database::getConnection();

            $sql = "SELECT * FROM services WHERE serviceid = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                return null;
            }

            $stmt->bind_param("i", $serviceId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $row = $result->fetch_assoc();
            $stmt->close();

            return new ServiceDetail($row);

        } catch (Exception $e) {
            error_log("Error getting service detail: " . $e->getMessage());
            return null;
        }
    }
    
    public function incrementServiceViewCount($serviceId) {
        try {
            $conn = Database::getConnection();
    
            // Check if the service exists
            $checkSql = "SELECT serviceid FROM services WHERE serviceid = ?";
            $checkStmt = $conn->prepare($checkSql);
            if (!$checkStmt) {
                error_log("Prepare failed: " . $conn->error);
                return false;
            }
            $checkStmt->bind_param("i", $serviceId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $checkStmt->close();
    
            if ($result->num_rows > 0) {
                // Service exists, increment the view count
                $sql = "UPDATE services SET view_count = view_count + 1 WHERE serviceid = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log("Prepare failed: " . $conn->error);
                    return false;
                }
                $stmt->bind_param("i", $serviceId);
                $success = $stmt->execute();
                $stmt->close();
                return $success;
            } else {
                error_log("Service ID not found in database: " . $serviceId);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error incrementing view count: " . $e->getMessage());
            return false;
        }
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
    
    public function getServicesByCleaner($cleanerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ?");
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $services = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $services;
        } catch (Exception $e) {
            error_log("Error getting services by cleaner: " . $e->getMessage());
            return [];
        }
    }
    
    public function getServicesByCleanerFormatted($cleanerId) {
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
    
    public function createService($cleanerId, $title, $description, $price, $availability, $category, $image_path) {
        try {
            $conn = Database::getConnection();
            $sql = "INSERT INTO services (cleanerid, title, description, price, availability, category, image_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issdss", $cleanerId, $title, $description, $price, $availability, $category, $image_path);
            $success = $stmt->execute();
            $serviceId = $conn->insert_id;
            $stmt->close();
            
            return $success ? $serviceId : false;
        } catch (Exception $e) {
            error_log("Error creating service: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateService($serviceId, $title, $description, $price, $availability, $category, $image_path) {
        try {
            $conn = Database::getConnection();
            $sql = "UPDATE services SET title = ?, description = ?, price = ?, 
                    availability = ?, category = ?, image_path = ? WHERE serviceid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $title, $description, $price, $availability, $category, $image_path, $serviceId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (Exception $e) {
            error_log("Error updating service: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteService($serviceId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM services WHERE serviceid = ?");
            $stmt->bind_param("i", $serviceId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (Exception $e) {
            error_log("Error deleting service: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllCategories() {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT name FROM service_categories");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row['name'];
            }
            
            $stmt->close();
            return $categories;
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cleaner-related methods
     */
    
    public function getCleanerById($cleanerId) {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT u.userid, u.username, u.email, u.status 
                    FROM users u 
                    WHERE u.userid = ? AND u.role = 'Cleaner'";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $row = $result->fetch_assoc();
            
            // Check if cleaner is active
            if ($row['status'] !== 'active') {
                return null;
            }
            
            $cleaner = new Cleaner();
            $cleaner->id = $row['userid'];
            $cleaner->name = $row['username'];
            $cleaner->email = $row['email'];
            $cleaner->status = $row['status'];
            
            // Get cleaner services
            $services = $this->getServicesByCleaner($cleaner->id);
            
            foreach ($services as $serviceData) {
                $serviceObj = new CleanerService(
                    $serviceData['serviceid'],
                    $serviceData['cleanerid'],
                    $serviceData['title'],
                    $serviceData['price'],
                    $serviceData['description'],
                    $serviceData['availability'],
                    $serviceData['category'],
                    $serviceData['image_path']
                );
                $cleaner->services[] = $serviceObj;
            }
            
            return $cleaner;
        } catch (Exception $e) {
            error_log("Error getting cleaner: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAllCleaners($sortBy = 'name') {
        try {
            $conn = Database::getConnection();
            $orderClause = $this->getSortClause($sortBy);
            
            $sql = "SELECT u.userid, u.username, 
                    (SELECT AVG(price) FROM services WHERE cleanerid = u.userid) as avg_price
                    FROM users u 
                    WHERE u.role = 'Cleaner' AND u.status = 'active'
                    $orderClause";
                    
            $result = $conn->query($sql);
            
            return $this->processCleanerResults($result);
        } catch (Exception $e) {
            error_log("Error getting all cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    public function searchCleaners($searchTerm, $category, $sortBy = 'name') {
        try {
            $conn = Database::getConnection();
            $orderClause = $this->getSortClause($sortBy);
            
            $sql = "SELECT DISTINCT u.userid, u.username, 
                    (SELECT AVG(price) FROM services WHERE cleanerid = u.userid) as avg_price
                    FROM users u 
                    LEFT JOIN services s ON u.userid = s.cleanerid
                    WHERE u.role = 'Cleaner' AND u.status = 'active'";
            
            $params = [];
            $types = "";
            
            if (!empty($searchTerm)) {
                $searchTerm = "%" . $searchTerm . "%";
                $sql .= " AND u.username LIKE ?";
                $params[] = $searchTerm;
                $types .= "s";
            }
            
            if (!empty($category)) {
                $sql .= " AND s.category = ?";
                $params[] = $category;
                $types .= "s";
            }
            
            $sql .= " $orderClause";
            
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $this->processCleanerResults($result);
        } catch (Exception $e) {
            error_log("Error searching cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    private function getSortClause($sortBy) {
        switch ($sortBy) {
            case 'price':
                return "ORDER BY avg_price ASC";
            case 'name':
            default:
                return "ORDER BY u.username ASC";
        }
    }
    
    private function processCleanerResults($result) {
        try {
            $cleaners = [];
            
            while ($row = $result->fetch_assoc()) {
                $cleaner = new Cleaner();
                $cleaner->id = $row['userid'];
                $cleaner->name = $row['username'];
                
                // Get cleaner email
                $conn = Database::getConnection();
                $emailQuery = "SELECT email FROM users WHERE userid = ?";
                $stmt = $conn->prepare($emailQuery);
                $stmt->bind_param("i", $cleaner->id);
                $stmt->execute();
                $emailResult = $stmt->get_result();
                $emailRow = $emailResult->fetch_assoc();
                $cleaner->email = $emailRow['email'];
                
                // Get services
                $services = $this->getServicesByCleaner($cleaner->id);
                
                foreach ($services as $serviceData) {
                    $serviceObj = new CleanerService(
                        $serviceData['serviceid'],
                        $serviceData['cleanerid'],
                        $serviceData['title'],
                        $serviceData['price'],
                        $serviceData['description'],
                        $serviceData['availability'],
                        $serviceData['category'],
                        $serviceData['image_path']
                    );
                    $cleaner->services[] = $serviceObj;
                }
                
                $cleaners[] = $cleaner;
            }
            
            return $cleaners;
        } catch (Exception $e) {
            error_log("Error processing cleaner results: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Homeowner-related methods
     */
    
    public function getHomeownerById($homeownerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT userid, username, email
                FROM users
                WHERE userid = ? AND role = 'Homeowner' AND status = 'active'
            ");
            
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                
                return new Homeowner($row);
            }
            
            $stmt->close();
            return null;
        } catch (Exception $e) {
            error_log("Error getting homeowner: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateHomeowner($homeownerId, $name, $email) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE userid = ?");
            $stmt->bind_param("ssi", $name, $email, $homeownerId);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error updating homeowner: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateHomeownerWithPassword($homeownerId, $name, $email, $newPassword) {
        try {
            $conn = Database::getConnection();
            
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE userid = ?");
            $stmt->bind_param("sssi", $name, $email, $hashedPassword, $homeownerId);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error updating homeowner with password: " . $e->getMessage());
            return false;
        }
    }
    
    public function verifyPassword($homeownerId, $password) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT password FROM users WHERE userid = ?");
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                
                // Verify the password
                return password_verify($password, $row['password']);
            }
            
            $stmt->close();
            return false;
        } catch (Exception $e) {
            error_log("Error verifying password: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Shortlist-related methods
     */
    
    public function getShortlistedCleanersCount($homeownerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count
                FROM shortlists
                WHERE homeownerid = ?
            ");
            
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $count = $row['count'];
                $stmt->close();
                return $count;
            }
            
            $stmt->close();
            return 0;
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaners count: " . $e->getMessage());
            return 0;
        }
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT shortlistid 
                FROM shortlists 
                WHERE homeownerid = ? AND cleanerid = ?
            ");
            
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $isShortlisted = ($result && $result->num_rows > 0);
            $stmt->close();
            
            return $isShortlisted;
        } catch (Exception $e) {
            error_log("Error checking if shortlisted: " . $e->getMessage());
            return false;
        }
    }
    
    public function addToShortlist($homeownerId, $cleanerId) {
        try {
            // Don't add if already shortlisted
            if ($this->isShortlisted($cleanerId, $homeownerId)) {
                return true;
            }
            
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO shortlists (homeownerid, cleanerid) VALUES (?, ?)");
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (Exception $e) {
            error_log("Error adding to shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeFromShortlist($homeownerId, $cleanerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $success = $stmt->execute();
            $stmt->close();
            
            return $success;
        } catch (Exception $e) {
            error_log("Error removing from shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT cleanerid FROM shortlists WHERE homeownerid = ?");
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $shortlistedIds = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $shortlistedIds[] = $row['cleanerid'];
                }
            }
            
            $stmt->close();
            return $shortlistedIds;
        } catch (Exception $e) {
            error_log("Error getting shortlisted cleaner IDs: " . $e->getMessage());
            return [];
        }
    }
}
?>