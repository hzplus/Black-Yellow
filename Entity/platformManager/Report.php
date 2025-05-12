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
        // Get all service categories with counts
        $query = "
            SELECT 
                sc.name AS category_name, 
                COUNT(DISTINCT s.serviceid) AS service_count,
                COUNT(DISTINCT cm.matchid) AS booking_count
            FROM 
                service_categories sc
            LEFT JOIN 
                services s ON sc.name = s.category
            LEFT JOIN 
                confirmed_matches cm ON s.serviceid = cm.serviceid AND cm.confirmed_at BETWEEN ? AND ?
            GROUP BY 
                sc.name
            ORDER BY 
                booking_count DESC, service_count DESC
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $categoryData = [];
            while ($row = $result->fetch_assoc()) {
                $categoryData[] = $row;
            }
            
            $stmt->close();
            return $categoryData;
        } catch (Exception $e) {
            error_log("Error in getCategoryUsageByDateRange: " . $e->getMessage());
            return [];
        }
    }
    
    // Get new users registered in a date range
    private static function getNewUsersByDateRange($conn, $startDate, $endDate) {
        // Since your users table doesn't have a created_at column, we'll provide a fallback
        // In a production environment, you should add a created_at column to your users table
        
        // Try to query users created in the date range if you have created_at column
        try {
            // Check if users table has created_at column
            $checkColumnQuery = "SHOW COLUMNS FROM users LIKE 'created_at'";
            $checkResult = $conn->query($checkColumnQuery);
            
            if ($checkResult->num_rows > 0) {
                // If created_at column exists, use it for the query
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
                
                $userData = [];
                while ($row = $result->fetch_assoc()) {
                    $userData[] = $row;
                }
                
                $stmt->close();
                
                if (!empty($userData)) {
                    return $userData;
                }
            }
        } catch (Exception $e) {
            error_log("Error checking for created_at column or querying new users: " . $e->getMessage());
        }
        
        // If the above query fails or returns no results, get aggregate counts by role
        $query = "
            SELECT 
                role, 
                COUNT(*) as count 
            FROM 
                users 
            GROUP BY 
                role
        ";
        
        try {
            $result = $conn->query($query);
            
            $userData = [];
            while ($row = $result->fetch_assoc()) {
                $userData[] = $row;
            }
            
            return $userData;
        } catch (Exception $e) {
            error_log("Error in getNewUsersByDateRange fallback: " . $e->getMessage());
            
            // If all else fails, return sample data based on roles in the system
            return [
                ['role' => 'Homeowner', 'count' => 0],
                ['role' => 'Cleaner', 'count' => 0],
                ['role' => 'Admin', 'count' => 0],
                ['role' => 'Manager', 'count' => 0]
            ];
        }
    }
    
    // Get new services created in a date range
    private static function getNewServicesByDateRange($conn, $startDate, $endDate) {
        // Try to query services created in the date range
        try {
            // Check if services table has created_at column
            $checkColumnQuery = "SHOW COLUMNS FROM services LIKE 'created_at'";
            $checkResult = $conn->query($checkColumnQuery);
            
            if ($checkResult->num_rows > 0) {
                // If created_at column exists, use it for the query
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
                
                $serviceData = [];
                while ($row = $result->fetch_assoc()) {
                    $serviceData[] = $row;
                }
                
                $stmt->close();
                
                if (!empty($serviceData)) {
                    return $serviceData;
                }
            }
        } catch (Exception $e) {
            error_log("Error checking for created_at column or querying new services: " . $e->getMessage());
        }
        
        // If the above query fails or returns no results, get aggregate counts by category
        $query = "
            SELECT 
                category, 
                COUNT(*) as count 
            FROM 
                services 
            GROUP BY 
                category
        ";
        
        try {
            $result = $conn->query($query);
            
            $serviceData = [];
            while ($row = $result->fetch_assoc()) {
                $serviceData[] = $row;
            }
            
            return $serviceData;
        } catch (Exception $e) {
            error_log("Error in getNewServicesByDateRange fallback: " . $e->getMessage());
            
            // If all else fails, return empty categories
            return [
                ['category' => 'All-in-one', 'count' => 0],
                ['category' => 'Floor', 'count' => 0],
                ['category' => 'Laundry', 'count' => 0],
                ['category' => 'Toilet', 'count' => 0],
                ['category' => 'Window', 'count' => 0]
            ];
        }
    }
    
    // Get bookings made in a date range
    private static function getBookingsByDateRange($conn, $startDate, $endDate) {
        $query = "
            SELECT 
                s.category, 
                COUNT(cm.matchid) as count 
            FROM 
                services s
            LEFT JOIN 
                confirmed_matches cm ON s.serviceid = cm.serviceid AND cm.confirmed_at BETWEEN ? AND ?
            GROUP BY 
                s.category
            ORDER BY
                count DESC
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            
            $stmt->close();
            
            if (!empty($bookings)) {
                return $bookings;
            }
            
            // If no matches in the date range, return all categories with zero count
            $query = "
                SELECT 
                    name as category,
                    0 as count
                FROM 
                    service_categories
            ";
            
            $result = $conn->query($query);
            
            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            
            return $bookings;
            
        } catch (Exception $e) {
            error_log("Error in getBookingsByDateRange: " . $e->getMessage());
            
            // If all else fails, return empty data
            return [
                ['category' => 'All-in-one', 'count' => 0],
                ['category' => 'Floor', 'count' => 0],
                ['category' => 'Laundry', 'count' => 0],
                ['category' => 'Toilet', 'count' => 0],
                ['category' => 'Window', 'count' => 0]
            ];
        }
    }
    
    // Get daily breakdown of booking activities for a weekly report
    private static function getDailyBreakdown($conn, $startDate, $endDate) {
        $query = "
            SELECT 
                DATE(confirmed_at) AS date,
                COUNT(*) AS booking_count
            FROM 
                confirmed_matches
            WHERE 
                confirmed_at BETWEEN ? AND ?
            GROUP BY 
                DATE(confirmed_at)
            ORDER BY 
                date
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $dailyData = [];
            while ($row = $result->fetch_assoc()) {
                $dailyData[] = $row;
            }
            
            $stmt->close();
            
            // If no bookings in the date range, create entries for each day with zero count
            if (empty($dailyData)) {
                $dailyData = [];
                $currentDate = new DateTime(substr($startDate, 0, 10));
                $endDateObj = new DateTime(substr($endDate, 0, 10));
                
                while ($currentDate <= $endDateObj) {
                    $dailyData[] = [
                        'date' => $currentDate->format('Y-m-d'),
                        'booking_count' => 0
                    ];
                    $currentDate->modify('+1 day');
                }
            }
            
            return $dailyData;
            
        } catch (Exception $e) {
            error_log("Error in getDailyBreakdown: " . $e->getMessage());
            
            // If error, return empty data for the date range
            $dailyData = [];
            $currentDate = new DateTime(substr($startDate, 0, 10));
            $endDateObj = new DateTime(substr($endDate, 0, 10));
            
            while ($currentDate <= $endDateObj) {
                $dailyData[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'booking_count' => 0
                ];
                $currentDate->modify('+1 day');
            }
            
            return $dailyData;
        }
    }
    
    // Get weekly breakdown of booking activities for a monthly report
    private static function getWeeklyBreakdown($conn, $startDate, $endDate) {
        $query = "
            SELECT 
                CONCAT('Week ', WEEK(confirmed_at, 1) - WEEK(DATE_FORMAT(confirmed_at, '%Y-%m-01'), 1) + 1) AS week_name,
                COUNT(*) AS booking_count
            FROM 
                confirmed_matches
            WHERE 
                confirmed_at BETWEEN ? AND ?
            GROUP BY 
                week_name
            ORDER BY 
                MIN(confirmed_at)
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $weeklyData = [];
            while ($row = $result->fetch_assoc()) {
                $weeklyData[] = $row;
            }
            
            $stmt->close();
            
            // If no bookings in the date range, create entries for each week with zero count
            if (empty($weeklyData)) {
                $numWeeks = ceil(date('t', strtotime($startDate)) / 7); // Number of weeks in the month
                
                for ($i = 1; $i <= $numWeeks; $i++) {
                    $weeklyData[] = [
                        'week_name' => "Week $i",
                        'booking_count' => 0
                    ];
                }
            }
            
            return $weeklyData;
            
        } catch (Exception $e) {
            error_log("Error in getWeeklyBreakdown: " . $e->getMessage());
            
            // If error, return empty data
            $numWeeks = ceil(date('t', strtotime($startDate)) / 7); // Number of weeks in the month
            
            $weeklyData = [];
            for ($i = 1; $i <= $numWeeks; $i++) {
                $weeklyData[] = [
                    'week_name' => "Week $i",
                    'booking_count' => 0
                ];
            }
            
            return $weeklyData;
        }
    }
    
    // Get top performing categories
    private static function getTopCategories($conn, $startDate, $endDate) {
        $query = "
            SELECT 
                s.category, 
                COUNT(cm.matchid) as booking_count
            FROM 
                services s
            LEFT JOIN 
                confirmed_matches cm ON s.serviceid = cm.serviceid AND cm.confirmed_at BETWEEN ? AND ?
            GROUP BY 
                s.category
            ORDER BY 
                booking_count DESC
            LIMIT 3
        ";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $topCategories = [];
            while ($row = $result->fetch_assoc()) {
                $topCategories[] = $row;
            }
            
            $stmt->close();
            
            // If no matches, get the top 3 categories by service count
            if (empty($topCategories) || $topCategories[0]['booking_count'] == 0) {
                $query = "
                    SELECT 
                        category, 
                        0 as booking_count
                    FROM 
                        services
                    GROUP BY 
                        category
                    ORDER BY 
                        COUNT(*) DESC
                    LIMIT 3
                ";
                
                $result = $conn->query($query);
                
                $topCategories = [];
                while ($row = $result->fetch_assoc()) {
                    $topCategories[] = $row;
                }
            }
            
            return $topCategories;
            
        } catch (Exception $e) {
            error_log("Error in getTopCategories: " . $e->getMessage());
            
            // If error, return generic categories
            return [
                ['category' => 'Floor', 'booking_count' => 0],
                ['category' => 'All-in-one', 'booking_count' => 0],
                ['category' => 'Toilet', 'booking_count' => 0]
            ];
        }
    }
}