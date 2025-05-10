<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Entity/service.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Entity/homeowner/Service.php');

class ViewServiceDetailsController {
    public static function getServiceById($serviceId) {
        // Get service data using CleanerService
        $service = new CleanerService(null, null, null, null, null, null);
        $serviceData = $service->getServiceById($serviceId);
        
        // If found, convert to Service entity object for view
        if ($serviceData) {
            $serviceEntity = new Service();
            $serviceEntity->setId($serviceData['serviceid']);
            $serviceEntity->setTitle($serviceData['title']);
            $serviceEntity->setDescription($serviceData['description']);
            $serviceEntity->setPrice($serviceData['price']);
            $serviceEntity->setAvailability($serviceData['availability']);
            $serviceEntity->setCategory($serviceData['category'] ?? null);
            $serviceEntity->setImagePath($serviceData['image_path'] ?? null);
            $serviceEntity->setCleanerId($serviceData['cleanerid']);
            $serviceEntity->setCleanerName($serviceData['cleaner_name']);
            $serviceEntity->setCleanerEmail($serviceData['cleaner_email']);
            $serviceEntity->setViewCount($serviceData['view_count'] ?? 0);
            $serviceEntity->setShortlistCount($serviceData['shortlist_count'] ?? 0);
            
            return $serviceEntity;
        }
        
        return null;
    }
    
    public static function incrementViewCount($serviceId) {
        // Use the CleanerService to increment view count
        $service = new CleanerService(null, null, null, null, null, null);
        
        // Check if view_count column exists and increment
        $conn = Database::getConnection();
        $checkColumnQuery = "SHOW COLUMNS FROM services LIKE 'view_count'";
        $checkResult = $conn->query($checkColumnQuery);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE services SET view_count = view_count + 1 WHERE serviceid = ?");
            $stmt->bind_param("i", $serviceId);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
        
        // If column doesn't exist, add it
        $conn->query("ALTER TABLE services ADD COLUMN view_count INT DEFAULT 0");
        $stmt = $conn->prepare("UPDATE services SET view_count = 1 WHERE serviceid = ?");
        $stmt->bind_param("i", $serviceId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public static function bookService($homeownerId, $cleanerId, $serviceId, $bookingDate) {
        // Book service using database connection
        $conn = Database::connect();
        $stmt = $conn->prepare("INSERT INTO match_history (cleanerid, homeownerid, serviceid, match_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $cleanerId, $homeownerId, $serviceId, $bookingDate);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    public static function isCleanerShortlisted($cleanerId, $homeownerId) {
        // Check if cleaner is shortlisted
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT 1 FROM shortlists WHERE cleanerid = ? AND homeownerid = ?");
        $stmt->bind_param("ii", $cleanerId, $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isShortlisted = $result->num_rows > 0;
        $stmt->close();
        return $isShortlisted;
    }
    
    public static function toggleShortlist($cleanerId, $homeownerId) {
        $conn = Database::connect();
        
        // Check if already shortlisted
        $stmt = $conn->prepare("SELECT 1 FROM shortlists WHERE cleanerid = ? AND homeownerid = ?");
        $stmt->bind_param("ii", $cleanerId, $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isShortlisted = $result->num_rows > 0;
        $stmt->close();
        
        if ($isShortlisted) {
            // Remove from shortlist
            $stmt = $conn->prepare("DELETE FROM shortlists WHERE cleanerid = ? AND homeownerid = ?");
            $stmt->bind_param("ii", $cleanerId, $homeownerId);
            $success = $stmt->execute();
            $stmt->close();
            
            // Update shortlist count if column exists
            $checkColumnQuery = "SHOW COLUMNS FROM services LIKE 'shortlist_count'";
            $checkResult = $conn->query($checkColumnQuery);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE services SET shortlist_count = GREATEST(shortlist_count - 1, 0) WHERE cleanerid = ?");
                $stmt->bind_param("i", $cleanerId);
                $stmt->execute();
                $stmt->close();
            }
            
            return $success ? "removed" : false;
        } else {
            // Add to shortlist
            $stmt = $conn->prepare("INSERT INTO shortlists (cleanerid, homeownerid) VALUES (?, ?)");
            $stmt->bind_param("ii", $cleanerId, $homeownerId);
            $success = $stmt->execute();
            $stmt->close();
            
            // Update shortlist count if column exists
            $checkColumnQuery = "SHOW COLUMNS FROM services LIKE 'shortlist_count'";
            $checkResult = $conn->query($checkColumnQuery);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE services SET shortlist_count = shortlist_count + 1 WHERE cleanerid = ?");
                $stmt->bind_param("i", $cleanerId);
                $stmt->execute();
                $stmt->close();
            }
            
            return $success ? "added" : false;
        }
    }
}
?>