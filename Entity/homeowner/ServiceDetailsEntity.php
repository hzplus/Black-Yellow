<?php
// Entity/ServiceDetailsEntity.php
require_once __DIR__ . '/../../db/Database.php';

class ServiceDetailsEntity {
    /**
     * Get a service by ID
     * 
     * @param int $serviceId Service ID to retrieve
     * @return array|null Service data or null if not found
     */
    public function getServiceById(int $serviceId): ?array {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM services WHERE serviceid = ?");
            $stmt->bind_param("i", $serviceId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $service = $result->fetch_assoc();
            $stmt->close();
            
            return $service;
        } catch (\Exception $e) {
            error_log("Error getting service: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get cleaner information by ID
     * 
     * @param int $cleanerId Cleaner ID to retrieve
     * @return array|null Cleaner data or null if not found
     */
    public function getCleanerById(int $cleanerId): ?array {
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
            
            $cleaner = $result->fetch_assoc();
            
            // Check if cleaner is active
            if ($cleaner['status'] !== 'active') {
                return null;
            }
            
            $stmt->close();
            return $cleaner;
        } catch (\Exception $e) {
            error_log("Error getting cleaner: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Increment the view count for a service
     * 
     * @param int $serviceId Service ID to update
     * @return bool Success status
     */
    public function incrementViewCount(int $serviceId): bool {
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
        } catch (\Exception $e) {
            error_log("Error incrementing view count: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if cleaner is shortlisted by homeowner
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Whether cleaner is shortlisted
     */
    public function isCleanerShortlisted(int $cleanerId, int $homeownerId): bool {
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
        } catch (\Exception $e) {
            error_log("Error checking if shortlisted: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle a cleaner's shortlist status
     * 
     * @param int $cleanerId Cleaner ID
     * @param int $homeownerId Homeowner ID
     * @return bool Success status
     */
    public function toggleShortlist(int $cleanerId, int $homeownerId): bool {
        try {
            $conn = Database::getConnection();
            
            // Check if already shortlisted
            $isShortlisted = $this->isCleanerShortlisted($cleanerId, $homeownerId);
            
            if ($isShortlisted) {
                // Remove from shortlist
                $stmt = $conn->prepare("DELETE FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
                $stmt->bind_param("ii", $homeownerId, $cleanerId);
                $success = $stmt->execute();
                $stmt->close();
            } else {
                // Add to shortlist
                $stmt = $conn->prepare("INSERT INTO shortlists (homeownerid, cleanerid) VALUES (?, ?)");
                $stmt->bind_param("ii", $homeownerId, $cleanerId);
                $success = $stmt->execute();
                $stmt->close();
            }
            
            return $success;
        } catch (\Exception $e) {
            error_log("Error toggling shortlist: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Book a service
     * 
     * @param int $serviceId Service ID to book
     * @param int $cleanerId Cleaner offering the service
     * @param int $homeownerId Homeowner booking the service
     * @param string $bookingDate Requested booking date
     * @param string $notes Optional booking notes
     * @return int|false Booking ID on success, false on failure
     */
    public function bookService(int $serviceId, int $cleanerId, int $homeownerId, string $bookingDate, string $notes = ''): int|false {
        try {
            $conn = Database::getConnection();
            
            // Begin transaction
            $conn->begin_transaction();
            
            // Insert into bookings table
            $stmt = $conn->prepare("
                INSERT INTO bookings (serviceid, cleanerid, homeownerid, booking_date, status, notes) 
                VALUES (?, ?, ?, ?, 'pending', ?)
            ");
            $stmt->bind_param("iiiss", $serviceId, $cleanerId, $homeownerId, $bookingDate, $notes);
            $success = $stmt->execute();
            
            if (!$success) {
                // Rollback transaction on failure
                $conn->rollback();
                return false;
            }
            
            $bookingId = $conn->insert_id;
            $stmt->close();
            
            // Commit transaction
            $conn->commit();
            
            return $bookingId;
        } catch (\Exception $e) {
            // Rollback transaction on exception
            if ($conn->connect_error === false) {
                $conn->rollback();
            }
            
            error_log("Error booking service: " . $e->getMessage());
            return false;
        }
    }
}