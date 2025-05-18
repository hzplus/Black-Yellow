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
            
            $sql = "SELECT cm.matchid, cm.homeownerid, cm.cleanerid, cm.serviceid, cm.booking_date as service_date, 
                          cm.confirmed_at, s.title as service_title, s.price, s.category, u.username as cleaner_name
                   FROM confirmed_matches cm
                   JOIN services s ON cm.serviceid = s.serviceid
                   JOIN users u ON cm.cleanerid = u.userid
                   WHERE cm.homeownerid = ?";
            
            $params = [$homeownerId];
            $types = "i";
            
            if (!empty($startDate)) {
                $sql .= " AND cm.booking_date >= ?";
                $params[] = $startDate;
                $types .= "s";
            }
            
            if (!empty($endDate)) {
                $sql .= " AND cm.booking_date <= ?";
                $params[] = $endDate;
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
            
            $sql .= " ORDER BY cm.booking_date DESC";
            
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
     * Get all cleaners the homeowner has worked with
     * 
     * @param int $homeownerId Homeowner ID
     * @return array Array of cleaners
     */
    public function getPreviousCleaners(int $homeownerId): array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT DISTINCT u.userid, u.username, COUNT(cm.matchid) as service_count
                    FROM users u
                    JOIN confirmed_matches cm ON u.userid = cm.cleanerid
                    WHERE cm.homeownerid = ?
                    GROUP BY u.userid, u.username
                    ORDER BY service_count DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $cleaners = [];
            while ($row = $result->fetch_assoc()) {
                $cleaners[] = $row;
            }
            
            $stmt->close();
            return $cleaners;
            
        } catch (\Exception $e) {
            error_log("Error getting previous cleaners: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all services a homeowner has received from a specific cleaner
     * 
     * @param int $homeownerId Homeowner ID
     * @param int $cleanerId Cleaner ID
     * @return array Array of services
     */
    public function getServicesFromCleaner(int $homeownerId, int $cleanerId): array {
        try {
            $conn = Database::getConnection();
            
            $sql = "SELECT cm.matchid, cm.booking_date, s.title, s.category, s.price
                    FROM confirmed_matches cm
                    JOIN services s ON cm.serviceid = s.serviceid
                    WHERE cm.homeownerid = ? AND cm.cleanerid = ?
                    ORDER BY cm.booking_date DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $homeownerId, $cleanerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $services = [];
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
            
            $stmt->close();
            return $services;
            
        } catch (\Exception $e) {
            error_log("Error getting services from cleaner: " . $e->getMessage());
            return [];
        }
    }
}