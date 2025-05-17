<?php
// Entity/ServiceHistoryEntity.php
require_once __DIR__ . '/../../db/Database.php';

class ServiceHistoryEntity {
    /**
     * Get service history for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $startDate Optional start date filter (Y-m-d)
     * @param string $endDate Optional end date filter (Y-m-d)
     * @param string $cleanerName Optional cleaner name filter
     * @param string $category Optional service category filter
     * @return array Array of service history records
     */
    public function getServiceHistory(
        int $homeownerId, 
        string $startDate = '', 
        string $endDate = '', 
        string $cleanerName = '', 
        string $category = ''
    ): array {
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
                $serviceHistory[] = $row;
            }
            
            $stmt->close();
            return $serviceHistory;
            
        } catch (\Exception $e) {
            error_log("Error getting service history: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all available service categories
     * 
     * @return array Array of category names
     */
    public function getAllCategories(): array {
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
        } catch (\Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get pending bookings for a homeowner
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of pending booking records
     */
    public function getPendingBookings(int $homeownerId): array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT b.bookingid, b.cleanerid, b.serviceid, b.booking_date, b.notes,
                          s.title, s.price, s.category, u.username as cleaner_name
                   FROM bookings b
                   JOIN services s ON b.serviceid = s.serviceid
                   JOIN users u ON b.cleanerid = u.userid
                   WHERE b.homeownerid = ? AND b.status = 'pending'
                   ORDER BY b.booking_date ASC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $pendingBookings = [];
            while ($row = $result->fetch_assoc()) {
                $pendingBookings[] = $row;
            }
            
            $stmt->close();
            return $pendingBookings;
            
        } catch (\Exception $e) {
            error_log("Error getting pending bookings: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cancel a booking
     * 
     * @param int $bookingId Booking ID to cancel
     * @param int $homeownerId Homeowner ID for verification
     * @return bool Success status
     */
    public function cancelBooking(int $bookingId, int $homeownerId): bool {
        try {
            $conn = Database::getConnection();
            
            // Verify booking belongs to this homeowner
            $checkSql = "SELECT bookingid FROM bookings WHERE bookingid = ? AND homeownerid = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $bookingId, $homeownerId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $checkStmt->close();
            
            if ($result->num_rows === 0) {
                return false; // Booking not found or doesn't belong to this homeowner
            }
            
            // Update booking status to canceled
            $updateSql = "UPDATE bookings SET status = 'canceled' WHERE bookingid = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $bookingId);
            $success = $updateStmt->execute();
            $updateStmt->close();
            
            return $success;
            
        } catch (\Exception $e) {
            error_log("Error canceling booking: " . $e->getMessage());
            return false;
        }
    }
}