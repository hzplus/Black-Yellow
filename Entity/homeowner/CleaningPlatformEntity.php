<?php
// Entity/CleaningPlatformEntity.php

require_once __DIR__ . '/../../db/Database.php';
require_once __DIR__ . '/../homeowner/CleanerEntity.php';
require_once __DIR__ . '/../homeowner/ServiceEntity.php';

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
        $this->serviceid = $data['serviceid'];
        $this->cleanerid = $data['cleanerid'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->category = $data['category'];
        $this->view_count = $data['view_count'];
        $this->shortlist_count = $data['shortlist_count'];
        $this->image_path = $data['image_path'];
        $this->availability = $data['availability'];
    }

    public function getServiceId() {
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

class CleaningPlatformEntity {
    private $cleaner;

    public function __construct() {
        $this->cleaner = new Cleaner();
    }

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

    public function getCleanerById($cleanerId) {
        return $this->cleaner->getCleanerById($cleanerId);
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

    public function isShortlisted($cleanerId, $homeownerId) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT shortlistid FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();

            $isShortlisted = ($result->num_rows > 0);
            $stmt->close();

            return $isShortlisted;

        } catch (Exception $e) {
            error_log("Error checking shortlist: " . $e->getMessage());
            return false;
        }
    }

    public function addToShortlist($homeownerId, $cleanerId) {
        try {
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

    public function createBooking($homeownerId, $cleanerId, $serviceId, $bookingDateTime, $notes) {
        try {
            $conn = Database::getConnection();

            $sql = "INSERT INTO bookings (homeownerid, cleanerid, serviceid, booking_date, notes, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiss", $homeownerId, $cleanerId, $serviceId, $bookingDateTime, $notes);
            $success = $stmt->execute();
            $stmt->close();

            return $success;

        } catch (Exception $e) {
            error_log("Error creating booking: " . $e->getMessage());
            return false;
        }
    }
}
?>