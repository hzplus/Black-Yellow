<?php
require_once __DIR__ . '/../db/Database.php';

class ConfirmedMatch {
    public $matchId;
    public $cleanerId;
    public $homeownerId;
    public $serviceId;
    // public $confirmedAt;
    public $bookingDate;

    public function __construct($matchId, $cleanerId, $homeownerId, $serviceId, $bookingDate) {
        $this->matchId = $matchId;
        $this->cleanerId = $cleanerId;
        $this->homeownerId = $homeownerId;
        $this->serviceId = $serviceId;
        // $this->confirmedAt = $confirmedAt;
        $this->bookingDate = $bookingDate;
    }

    public function getConfirmedMatchesByCleaner($cleanerId, $category = null, $startDate = null, $endDate = null) {
        $conn = Database::getConnection();

        $query = "
            SELECT cm.booking_date, u.username AS homeowner_name, s.title, s.category
            FROM confirmed_matches cm
            JOIN services s ON cm.serviceid = s.serviceid
            JOIN users u ON cm.homeownerid = u.userid
            WHERE cm.cleanerid = ?

        ";

        $params = [$cleanerId];
        $types = "i";

        if (!empty($category)) {
            $query .= " AND s.category = ?";
            $types .= "s";
            $params[] = $category;
        }

        if (!empty($startDate)) {
            $query .= " AND cm.booking_date >= ?";
            $types .= "s";
            $params[] = $startDate;
        }

        if (!empty($endDate)) {
            $query .= " AND cm.booking_date <= ?";
            $types .= "s";
            $params[] = $endDate;
        }

        $query .= " ORDER BY cm.booking_date DESC";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $matches = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $matches;
    }

    public function searchConfirmedMatchesByCleaner($cleanerId, $keyword = '', $category = null, $startDate = null, $endDate = null) {
        $conn = Database::getConnection();
    
        $query = "
            SELECT cm.booking_date, u.username AS homeowner_name, s.title, s.category
            FROM confirmed_matches cm
            JOIN services s ON cm.serviceid = s.serviceid
            JOIN users u ON cm.homeownerid = u.userid
            WHERE cm.cleanerid = ? AND (s.title LIKE ? OR s.category LIKE ?)
        ";
    
        $params = [$cleanerId, "%$keyword%", "%$keyword%"];
        $types = "iss";
    
        if (!empty($category)) {
            $query .= " AND s.category = ?";
            $types .= "s";
            $params[] = $category;
        }
    
        if (!empty($startDate)) {
            $query .= " AND cm.booking_date >= ?";
            $types .= "s";
            $params[] = $startDate;
        }
    
        if (!empty($endDate)) {
            $query .= " AND cm.booking_date <= ?";
            $types .= "s";
            $params[] = $endDate;
        }
    
        $query .= " ORDER BY cm.booking_date DESC";
    
        $stmt = $conn->prepare($query);
        if (!$stmt) return [];
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $matches = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $matches;
    }
    


}
