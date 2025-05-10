<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Entity/service.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Entity/homeowner/Cleaner.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Entity/homeowner/Service.php');

class ViewCleanerProfileController {
    public static function getCleanerById($cleanerId) {
        // Get cleaner information using Database connection
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT userid, username, email, status FROM users WHERE userid = ? AND role = 'cleaner'");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $cleanerData = $result->fetch_assoc();
        $stmt->close();
        
        // Create Cleaner entity
        $cleaner = new Cleaner();
        $cleaner->setId($cleanerData['userid']);
        $cleaner->setName($cleanerData['username']);
        $cleaner->setEmail($cleanerData['email']);
        $cleaner->setStatus($cleanerData['status']);
        
        // Set default bio and profile picture
        $cleaner->setBio('No bio available for this cleaner.');
        $cleaner->setProfilePicture('default.jpg');
        
        // Get cleaner's services
        $service = new CleanerService(null, null, null, null, null, null);
        $services = $service->getServicesByCleaner($cleanerId);
        
        // Convert service data to Service entities
        $serviceEntities = [];
        foreach ($services as $serviceData) {
            $serviceEntity = new Service();
            $serviceEntity->setId($serviceData['serviceid']);
            $serviceEntity->setTitle($serviceData['title']);
            $serviceEntity->setDescription($serviceData['description']);
            $serviceEntity->setPrice($serviceData['price']);
            $serviceEntity->setAvailability($serviceData['availability']);
            $serviceEntity->setCategory($serviceData['category'] ?? null);
            $serviceEntity->setImagePath($serviceData['image_path'] ?? null);
            
            $serviceEntities[] = $serviceEntity;
        }
        
        $cleaner->setServices($serviceEntities);
        
        return $cleaner;
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