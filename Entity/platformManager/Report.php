<?php
require_once __DIR__ . '/../../db/Database.php';

class Report {
    
    // Generate daily report data
    public static function getDailyReport($date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        // Debug info
        error_log("Generating daily report for date: " . $date);
        
        $conn = Database::getConnection();
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        
        // Get service categories usage
        $categoriesData = self::getCategoryUsageByDateRange($conn, $startDate, $endDate);
        
        // Get new users registered
        $newUsers = self::getNewUsersByDateRange($conn, $startDate, $endDate);
        
        // Get new services created
        $newServices = self::getNewServicesByDateRange($conn, $startDate, $endDate);
        
        // Get bookings made
        $bookings = self::getBookingsByDateRange($conn, $startDate, $endDate);
        
        $report = [
            'date' => $date,
            'type' => 'daily',
            'categories_usage' => $categoriesData,
            'new_users' => $newUsers,
            'new_services' => $newServices,
            'bookings' => $bookings
        ];
        
        // Debug info
        error_log("Daily report data generated");
        
        return $report;
    }
    
    // Generate weekly report data
    public static function getWeeklyReport($date = null) {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        // Debug info
        error_log("Generating weekly report for date: " . $date);
        
        // Calculate the start and end of the week containing the given date
        $timestamp = strtotime($date);
        $startDate = date('Y-m-d', strtotime('this week monday', $timestamp));
        $endDate = date('Y-m-d', strtotime('this week sunday', $timestamp));
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';
        
        $conn = Database::getConnection();
        
        // Get service categories usage
        $categoriesData = self::getCategoryUsageByDateRange($conn, $startDateTime, $endDateTime);
        
        // Get new users registered
        $newUsers = self::getNewUsersByDateRange($conn, $startDateTime, $endDateTime);
        
        // Get new services created
        $newServices = self::getNewServicesByDateRange($conn, $startDateTime, $endDateTime);
        
        // Get bookings made
        $bookings = self::getBookingsByDateRange($conn, $startDateTime, $endDateTime);
        
        // Get daily breakdown
        $dailyBreakdown = self::getDailyBreakdown($conn, $startDateTime, $endDateTime);
        
        $report = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => 'weekly',
            'categories_usage' => $categoriesData,
            'new_users' => $newUsers,
            'new_services' => $newServices,
            'bookings' => $bookings,
            'daily_breakdown' => $dailyBreakdown
        ];
        
        // Debug info
        error_log("Weekly report data generated");
        
        return $report;
    }
    
    // Generate monthly report data
    public static function getMonthlyReport($month = null, $year = null) {
        if ($month === null) {
            $month = date('m');
        }
        if ($year === null) {
            $year = date('Y');
        }
        
        // Debug info
        error_log("Generating monthly report for month: " . $month . ", year: " . $year);
        
        $startDate = "$year-$month-01 00:00:00";
        $endDate = date('Y-m-t', strtotime($startDate)) . ' 23:59:59';
        
        $conn = Database::getConnection();
        
        // Get service categories usage
        $categoriesData = self::getCategoryUsageByDateRange($conn, $startDate, $endDate);
        
        // Get new users registered
        $newUsers = self::getNewUsersByDateRange($conn, $startDate, $endDate);
        
        // Get new services created
        $newServices = self::getNewServicesByDateRange($conn, $startDate, $endDate);
        
        // Get bookings made
        $bookings = self::getBookingsByDateRange($conn, $startDate, $endDate);
        
        // Get weekly breakdown
        $weeklyBreakdown = self::getWeeklyBreakdown($conn, $startDate, $endDate);
        
        // Get top performing categories
        $topCategories = self::getTopCategories($conn, $startDate, $endDate);
        
        $report = [
            'month' => $month,
            'year' => $year,
            'type' => 'monthly',
            'categories_usage' => $categoriesData,
            'new_users' => $newUsers,
            'new_services' => $newServices,
            'bookings' => $bookings,
            'weekly_breakdown' => $weeklyBreakdown,
            'top_categories' => $topCategories
        ];
        
        // Debug info
        error_log("Monthly report data generated");
        
        return $report;
    }
    
    // Get category usage statistics for a date range
    private static function getCategoryUsageByDateRange($conn, $startDate, $endDate) {
        // Get service categories from category table
        $categoriesQuery = "SELECT name FROM service_categories ORDER BY name";
        $result = $conn->query($categoriesQuery);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['name'];
        }
        
        // Now get service counts for each category within the date range
        $categoryData = [];
        
        foreach ($categories as $categoryName) {
            // Count active services in this category
            $query = "
                SELECT 
                    COUNT(DISTINCT s.serviceid) AS service_count
                FROM 
                    services s
                WHERE 
                    s.category = ? AND s.created_at BETWEEN ? AND ?
            ";
            
            try {
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $categoryName, $startDate, $endDate);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                $categoryData[] = [
                    'category_name' => $categoryName,
                    'service_count' => $row['service_count'] ?? 0
                ];
                
                $stmt->close();
            } catch (Exception $e) {
                error_log("Error in getCategoryUsageByDateRange for category $categoryName: " . $e->getMessage());
                // Add category with 0 count if there's an error
                $categoryData[] = [
                    'category_name' => $categoryName,
                    'service_count' => 0
                ];
            }
        }
        
        // Sort by service count (descending)
        usort($categoryData, function($a, $b) {
            return $b['service_count'] - $a['service_count'];
        });
        
        return $categoryData;
    }
    
    // Get new users registered in a date range
    private static function getNewUsersByDateRange($conn, $startDate, $endDate) {
        // Get all possible user roles
        $roleQuery = "SELECT DISTINCT role FROM users";
        $roleResult = $conn->query($roleQuery);
        
        $roles = [];
        while ($row = $roleResult->fetch_assoc()) {
            $roles[] = $row['role'];
        }
        
        // Prepare data structure with zero counts
        $userData = [];
        foreach ($roles as $role) {
            $userData[$role] = [
                'role' => $role,
                'count' => 0
            ];
        }
        
        // Now try to get actual counts for the date range
        try {
            $query = "
                SELECT 
                    role, 
                    COUNT(*) as count 
                FROM 
                    users 
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    role
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $userData[$row['role']] = $row;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in getNewUsersByDateRange: " . $e->getMessage());
            // On error, we'll use the default zero counts
        }
        
        // Convert to indexed array
        return array_values($userData);
    }
    
    // Get new services created in a date range
    private static function getNewServicesByDateRange($conn, $startDate, $endDate) {
        // Get all possible service categories
        $categoryQuery = "SELECT DISTINCT category FROM services UNION SELECT name FROM service_categories";
        $categoryResult = $conn->query($categoryQuery);
        
        $categories = [];
        while ($row = $categoryResult->fetch_assoc()) {
            if (!empty($row['category'])) {
                $categories[] = $row['category'];
            }
        }
        
        // Prepare data structure with zero counts
        $serviceData = [];
        foreach ($categories as $category) {
            $serviceData[$category] = [
                'category' => $category,
                'count' => 0
            ];
        }
        
        // Now try to get actual counts for the date range
        try {
            $query = "
                SELECT 
                    category, 
                    COUNT(*) as count 
                FROM 
                    services 
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    category
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $serviceData[$row['category']] = $row;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in getNewServicesByDateRange: " . $e->getMessage());
            // On error, we'll use the default zero counts
        }
        
        // Convert to indexed array
        return array_values($serviceData);
    }
    
    // Get bookings made in a date range
    private static function getBookingsByDateRange($conn, $startDate, $endDate) {
        // Get all possible service categories
        $categoryQuery = "SELECT DISTINCT category FROM services UNION SELECT name FROM service_categories";
        $categoryResult = $conn->query($categoryQuery);
        
        $categories = [];
        while ($row = $categoryResult->fetch_assoc()) {
            if (!empty($row['category'])) {
                $categories[] = $row['category'];
            }
        }
        
        // Prepare data structure with zero counts
        $bookingData = [];
        foreach ($categories as $category) {
            $bookingData[$category] = [
                'category' => $category,
                'count' => 0
            ];
        }
        
        // Now try to get actual counts for the date range
        try {
            $query = "
                SELECT 
                    s.category, 
                    COUNT(cm.matchid) as count 
                FROM 
                    services s
                JOIN 
                    confirmed_matches cm ON s.serviceid = cm.serviceid
                WHERE 
                    cm.confirmed_at BETWEEN ? AND ?
                GROUP BY 
                    s.category
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $bookingData[$row['category']] = $row;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in getBookingsByDateRange: " . $e->getMessage());
            // On error, we'll use the default zero counts
        }
        
        // Convert to indexed array and sort by count
        $bookings = array_values($bookingData);
        usort($bookings, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return $bookings;
    }
    
    // Get daily breakdown of activities for a weekly report
    private static function getDailyBreakdown($conn, $startDateTime, $endDateTime) {
        // First, get dates for the entire week
        $startDate = substr($startDateTime, 0, 10);
        $endDate = substr($endDateTime, 0, 10);
        
        // Create an array to hold all days in the week
        $daysInRange = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
        
        while ($currentDate <= $endDateObj) {
            $dateStr = $currentDate->format('Y-m-d');
            $daysInRange[$dateStr] = [
                'date' => $dateStr,
                'new_services_count' => 0,
                'new_users_count' => 0
            ];
            $currentDate->modify('+1 day');
        }
        
        // Query for new services by day
        try {
            $query = "
                SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as count
                FROM 
                    services
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDateTime, $endDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (isset($daysInRange[$row['date']])) {
                    $daysInRange[$row['date']]['new_services_count'] = $row['count'];
                }
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error getting daily services breakdown: " . $e->getMessage());
        }
        
        // Query for new users by day
        try {
            $query = "
                SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as count
                FROM 
                    users
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    DATE(created_at)
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDateTime, $endDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (isset($daysInRange[$row['date']])) {
                    $daysInRange[$row['date']]['new_users_count'] = $row['count'];
                }
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error getting daily users breakdown: " . $e->getMessage());
        }
        
        // Convert associative array to indexed array
        return array_values($daysInRange);
    }
    
    // Get weekly breakdown of activities for a monthly report
    private static function getWeeklyBreakdown($conn, $startDateTime, $endDateTime) {
        // Create an array for weeks in the month (typically 4-5 weeks)
        $weeks = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $weeks["Week $i"] = [
                'week_name' => "Week $i",
                'new_services_count' => 0,
                'new_users_count' => 0
            ];
        }
        
        // Query for new services by week
        try {
            $query = "
                SELECT 
                    CONCAT('Week ', WEEK(created_at, 1) - WEEK(DATE_FORMAT(created_at, '%Y-%m-01'), 1) + 1) AS week_name,
                    COUNT(*) AS count
                FROM 
                    services
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    week_name
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDateTime, $endDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (isset($weeks[$row['week_name']])) {
                    $weeks[$row['week_name']]['new_services_count'] = $row['count'];
                }
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error getting weekly services breakdown: " . $e->getMessage());
        }
        
        // Query for new users by week
        try {
            $query = "
                SELECT 
                    CONCAT('Week ', WEEK(created_at, 1) - WEEK(DATE_FORMAT(created_at, '%Y-%m-01'), 1) + 1) AS week_name,
                    COUNT(*) AS count
                FROM 
                    users
                WHERE 
                    created_at BETWEEN ? AND ?
                GROUP BY 
                    week_name
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDateTime, $endDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (isset($weeks[$row['week_name']])) {
                    $weeks[$row['week_name']]['new_users_count'] = $row['count'];
                }
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error getting weekly users breakdown: " . $e->getMessage());
        }
        
        // Remove weeks that are beyond the actual number of weeks in the month
        $finalWeeks = [];
        $actualWeeks = ceil(date('t', strtotime(substr($startDateTime, 0, 10))) / 7);
        
        for ($i = 1; $i <= min(5, $actualWeeks); $i++) {
            $finalWeeks[] = $weeks["Week $i"];
        }
        
        return $finalWeeks;
    }
    
    // Get top performing categories
    private static function getTopCategories($conn, $startDate, $endDate) {
        // Get all possible service categories
        $categoryQuery = "SELECT DISTINCT category FROM services UNION SELECT name FROM service_categories";
        $categoryResult = $conn->query($categoryQuery);
        
        $categories = [];
        while ($row = $categoryResult->fetch_assoc()) {
            if (!empty($row['category'])) {
                $categories[] = $row['category'];
            }
        }
        
        // Prepare data structure with zero counts
        $topCategoryData = [];
        foreach ($categories as $category) {
            $topCategoryData[$category] = [
                'category' => $category,
                'service_count' => 0
            ];
        }
        
        // Get bookings counts for the date range
        try {
            $query = "
                SELECT 
                    s.category, 
                    COUNT(cm.matchid) as service_count
                FROM 
                    services s
                JOIN 
                    confirmed_matches cm ON s.serviceid = cm.serviceid
                WHERE 
                    cm.confirmed_at BETWEEN ? AND ?
                GROUP BY 
                    s.category
            ";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $topCategoryData[$row['category']] = $row;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error in getTopCategories: " . $e->getMessage());
        }
        
        // Convert to indexed array, sort by service count, and get top 3
        $topCategories = array_values($topCategoryData);
        usort($topCategories, function($a, $b) {
            return $b['service_count'] - $a['service_count'];
        });
        
        return array_slice($topCategories, 0, 3);
    }
}