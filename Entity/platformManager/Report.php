<?php
// Entity/platformManager/Report.php
require_once __DIR__ . '/../../db/Database.php';

// Memory optimization for large reports
ini_set('memory_limit', '512M');

class Report {
    
    /**
     * Get a daily report for the specified date
     * 
     * @param string|null $date Date in Y-m-d format, defaults to today
     * @return array Report data
     */
    public static function getDailyReport($date = null) {
        $conn = Database::getConnection();
        
        // Default to today if no date provided
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        $report = [
            'date' => $date,
            'stats' => [
                'new_users_count' => 0,
                'new_services_count' => 0,
                'active_categories_count' => 0
            ],
            'categories_usage' => [],
            'new_users' => []
        ];
        
        // Start and end of the day
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        
        // Get new users count
        try {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE created_at BETWEEN ? AND ?
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $report['stats']['new_users_count'] = (int)$row['count'];
            }
            
            // Get new users by role
            $stmt = $conn->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY role
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_users'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting daily user stats: " . $e->getMessage());
        }
        
        // Get new services count
        try {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM services 
                WHERE created_at BETWEEN ? AND ?
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $report['stats']['new_services_count'] = (int)$row['count'];
            }
            
            // Get new services by category
            $stmt = $conn->prepare("
                SELECT category, COUNT(*) as count 
                FROM services 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY category
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_services'][] = [
                    'category' => $row['category'] ? $row['category'] : 'Uncategorized',
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting daily service stats: " . $e->getMessage());
        }
        
        // Get category usage - Use a CURRENT query instead of filtering by timestamp
        try {
            // First get all available categories
            $categoriesResult = $conn->query("SELECT name FROM service_categories");
            $categories = [];
            while ($catRow = $categoriesResult->fetch_assoc()) {
                $categories[] = $catRow['name'];
            }
            
            // For each category, count services created today
            foreach ($categories as $categoryName) {
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as service_count
                    FROM services
                    WHERE category = ? AND created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("sss", $categoryName, $startDate, $endDate);
                $stmt->execute();
                $serviceResult = $stmt->get_result();
                $serviceCount = 0;
                if ($serviceRow = $serviceResult->fetch_assoc()) {
                    $serviceCount = (int)$serviceRow['service_count'];
                }
                
                $report['categories_usage'][] = [
                    'category_name' => $categoryName,
                    'service_count' => $serviceCount
                ];
            }
            
            // Count active categories (those with at least one service)
            $activeCount = 0;
            foreach ($report['categories_usage'] as $category) {
                if ($category['service_count'] > 0) {
                    $activeCount++;
                }
            }
            $report['stats']['active_categories_count'] = $activeCount;
        } catch (Exception $e) {
            error_log("Error getting daily category stats: " . $e->getMessage());
        }
        
        return $report;
    }
    
    /**
     * Get a weekly report for the week containing the specified date
     * 
     * @param string|null $date Any date within the desired week (Y-m-d format)
     * @return array Report data
     */
    public static function getWeeklyReport($date = null) {
        $conn = Database::getConnection();
        
        // Default to today if no date provided
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        // Calculate start and end of the week containing the specified date
        $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($date)));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
        
        $report = [
            'start_date' => $startOfWeek,
            'end_date' => $endOfWeek,
            'daily_breakdown' => [],
            'categories_usage' => [],
            'new_users' => [],
            'new_services' => []
        ];
        
        // Start and end timestamps for queries
        $startTimestamp = $startOfWeek . ' 00:00:00';
        $endTimestamp = $endOfWeek . ' 23:59:59';
        
        // Get daily breakdown
        try {
            // Create array for each day of the week
            $currentDay = $startOfWeek;
            while ($currentDay <= $endOfWeek) {
                $dayStart = $currentDay . ' 00:00:00';
                $dayEnd = $currentDay . ' 23:59:59';
                
                // Get service count for the day
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM services 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $dayStart, $dayEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $serviceCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $serviceCount = (int)$row['count'];
                }
                
                // Get user count for the day
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM users 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $dayStart, $dayEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $userCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $userCount = (int)$row['count'];
                }
                
                $report['daily_breakdown'][] = [
                    'date' => $currentDay,
                    'new_services_count' => $serviceCount,
                    'new_users_count' => $userCount
                ];
                
                // Move to next day
                $currentDay = date('Y-m-d', strtotime($currentDay . ' +1 day'));
            }
        } catch (Exception $e) {
            error_log("Error getting weekly daily breakdown: " . $e->getMessage());
        }
        
        // Get category usage for the week
        try {
            // First get all available categories
            $categoriesResult = $conn->query("SELECT name FROM service_categories");
            $categories = [];
            while ($catRow = $categoriesResult->fetch_assoc()) {
                $categories[] = $catRow['name'];
            }
            
            // For each category, count services created this week
            foreach ($categories as $categoryName) {
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as service_count
                    FROM services
                    WHERE category = ? AND created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("sss", $categoryName, $startTimestamp, $endTimestamp);
                $stmt->execute();
                $serviceResult = $stmt->get_result();
                $serviceCount = 0;
                if ($serviceRow = $serviceResult->fetch_assoc()) {
                    $serviceCount = (int)$serviceRow['service_count'];
                }
                
                $report['categories_usage'][] = [
                    'category_name' => $categoryName,
                    'service_count' => $serviceCount
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting weekly category usage: " . $e->getMessage());
        }
        
        // Get new users by role for the week
        try {
            $stmt = $conn->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY role
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_users'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting weekly user stats: " . $e->getMessage());
        }
        
        // Get new services by category for the week
        try {
            $stmt = $conn->prepare("
                SELECT category, COUNT(*) as count 
                FROM services 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY category
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_services'][] = [
                    'category' => $row['category'] ? $row['category'] : 'Uncategorized',
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting weekly service stats: " . $e->getMessage());
        }
        
        return $report;
    }

    /**
     * Get a monthly report for the specified month and year
     * Memory-optimized version to prevent exhaustion
     * 
     * @param string|null $month Month (01-12), defaults to current month
     * @param string|null $year Year (YYYY), defaults to current year
     * @return array Report data
     */
    public static function getMonthlyReport($month = null, $year = null) {
        $conn = Database::getConnection();
        
        // Default to current month/year if not provided
        if ($month === null) {
            $month = date('m');
        }
        if ($year === null) {
            $year = date('Y');
        }
        
        // Ensure month is in two-digit format
        $month = sprintf('%02d', $month);
        
        // Calculate start and end dates
        $startDate = "$year-$month-01";
        $lastDay = date('t', strtotime($startDate)); // Get number of days in month
        $endDate = "$year-$month-$lastDay";
        
        $report = [
            'month' => $month,
            'year' => $year,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'weekly_breakdown' => [],
            'categories_usage' => [],
            'top_categories' => [],
            'new_users' => [],
            'new_services' => []
        ];
        
        // Start and end timestamps for queries
        $startTimestamp = $startDate . ' 00:00:00';
        $endTimestamp = $endDate . ' 23:59:59';
        
        // Memory-efficient approach: Get weekly breakdown directly from database
        try {
            // Get all weeks in the month more efficiently
            $firstDayOfMonth = new DateTime($startDate);
            $lastDayOfMonth = new DateTime($endDate);
            
            // Use manual approach to calculate weeks rather than recursive date manipulation
            $weekStart = clone $firstDayOfMonth;
            $weekNum = 1;
            
            // Process each week until we've covered the entire month
            while ($weekStart <= $lastDayOfMonth) {
                // Calculate the end of the week (Sunday) or month end, whichever comes first
                $weekEnd = clone $weekStart;
                
                // Get day of week (0 = Sunday, 6 = Saturday)
                $dayOfWeek = (int)$weekStart->format('w');
                
                // If not already Sunday, move to next Sunday
                if ($dayOfWeek > 0) {
                    $daysUntilSunday = 7 - $dayOfWeek;
                    $weekEnd->modify("+$daysUntilSunday days");
                } else {
                    $weekEnd->modify('+6 days'); // If already Sunday, week ends on Saturday
                }
                
                // Make sure week end doesn't exceed month end
                if ($weekEnd > $lastDayOfMonth) {
                    $weekEnd = clone $lastDayOfMonth;
                }
                
                // Format week info
                $weekStartStr = $weekStart->format('Y-m-d');
                $weekEndStr = $weekEnd->format('Y-m-d');
                $weekName = "Week $weekNum (" . $weekStart->format('M d') . ' - ' . $weekEnd->format('M d') . ')';
                
                // Get data for this week - combined query for efficiency
                $weekStartTime = $weekStartStr . ' 00:00:00';
                $weekEndTime = $weekEndStr . ' 23:59:59';
                
                // Query for user count
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM users 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $weekStartTime, $weekEndTime);
                $stmt->execute();
                $result = $stmt->get_result();
                $userCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $userCount = (int)$row['count'];
                }
                
                // Query for service count - reuse the statement
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM services 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $weekStartTime, $weekEndTime);
                $stmt->execute();
                $result = $stmt->get_result();
                $serviceCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $serviceCount = (int)$row['count'];
                }
                
                // Add to report
                $report['weekly_breakdown'][] = [
                    'week_name' => $weekName,
                    'new_services_count' => $serviceCount,
                    'new_users_count' => $userCount
                ];
                
                // Move to next week and increment count
                $weekStart = clone $weekEnd;
                $weekStart->modify('+1 day');
                $weekNum++;
                
                // Break the loop if we've gone past the month
                if ($weekStart > $lastDayOfMonth) {
                    break;
                }
            }
        } catch (Exception $e) {
            error_log("Error getting monthly weekly breakdown: " . $e->getMessage());
        }
        
        // Get category usage more efficiently
        try {
            // Use a single JOIN query instead of multiple queries
            $stmt = $conn->prepare("
                SELECT 
                    sc.name as category_name,
                    COUNT(s.serviceid) as service_count
                FROM 
                    service_categories sc
                LEFT JOIN 
                    services s ON sc.name = s.category AND s.created_at BETWEEN ? AND ?
                GROUP BY 
                    sc.name
                ORDER BY 
                    service_count DESC
            ");
            
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Process categories
            while ($row = $result->fetch_assoc()) {
                $report['categories_usage'][] = [
                    'category_name' => $row['category_name'],
                    'service_count' => (int)$row['service_count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting monthly category usage: " . $e->getMessage());
        }
        
        // Get top 5 categories
        try {
            $stmt = $conn->prepare("
                SELECT 
                    category, 
                    COUNT(*) as count 
                FROM 
                    services 
                WHERE 
                    created_at BETWEEN ? AND ? 
                    AND category IS NOT NULL 
                    AND category != ''
                GROUP BY 
                    category
                ORDER BY 
                    count DESC
                LIMIT 5
            ");
            
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['top_categories'][] = [
                    'category' => $row['category'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting top categories: " . $e->getMessage());
        }
        
        // Get new users by role
        try {
            $stmt = $conn->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY role
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_users'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting monthly user stats: " . $e->getMessage());
        }
        
        // Get new services by category
        try {
            $stmt = $conn->prepare("
                SELECT category, COUNT(*) as count 
                FROM services 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY category
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['new_services'][] = [
                    'category' => $row['category'] ? $row['category'] : 'Uncategorized',
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting monthly service stats: " . $e->getMessage());
        }
        
        return $report;
    }
    
    /**
     * Get platform growth statistics - Optimized for memory
     * 
     * @return array Platform growth statistics
     */
    public static function getPlatformStats() {
        $conn = Database::getConnection();
        $stats = [
            'users' => [
                'total' => 0,
                'this_month' => 0,
                'by_month' => [],
                'by_role' => []
            ],
            'services' => [
                'total' => 0,
                'this_month' => 0,
                'by_month' => [],
                'by_category' => []
            ],
            'popular_categories' => []
        ];
        
        // Get user and service totals in a more efficient way
        try {
            // Get total users and services in a single query
            $result = $conn->query("
                SELECT 
                    (SELECT COUNT(*) FROM users) as total_users,
                    (SELECT COUNT(*) FROM services) as total_services
            ");
            
            if ($row = $result->fetch_assoc()) {
                $stats['users']['total'] = (int)$row['total_users'];
                $stats['services']['total'] = (int)$row['total_services'];
            }
            
            // This month's users and services
            $currentMonth = date('Y-m-01');
            $nextMonth = date('Y-m-01', strtotime('+1 month'));
            
            $stmt = $conn->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM users WHERE created_at >= ? AND created_at < ?) as users_this_month,
                    (SELECT COUNT(*) FROM services WHERE created_at >= ? AND created_at < ?) as services_this_month
            ");
            
            $stmt->bind_param("ssss", $currentMonth, $nextMonth, $currentMonth, $nextMonth);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $stats['users']['this_month'] = (int)$row['users_this_month'];
                $stats['services']['this_month'] = (int)$row['services_this_month'];
            }
            
            // Users by role - using a single query
            $result = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            while ($row = $result->fetch_assoc()) {
                $stats['users']['by_role'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
            
            // Services by category
            $stmt = $conn->prepare("
                SELECT category, COUNT(*) as count 
                FROM services 
                WHERE category IS NOT NULL AND category != ''
                GROUP BY category
                ORDER BY count DESC
            ");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $categoryData = [];
            while ($row = $result->fetch_assoc()) {
                $categoryData[] = [
                    'category' => $row['category'],
                    'count' => (int)$row['count']
                ];
            }
            
            $stats['services']['by_category'] = $categoryData;
            
            // Top 5 categories
            $stats['popular_categories'] = array_slice($categoryData, 0, 5);
            
            // Get monthly data for the last 6 months in a more memory-efficient way
            $months = [];
            for ($i = 0; $i < 6; $i++) {
                $monthStart = date('Y-m-01', strtotime("-$i month"));
                $monthEnd = date('Y-m-01', strtotime("-" . ($i - 1) . " month"));
                $monthLabel = date('M Y', strtotime($monthStart));
                
                // Get user count
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM users 
                    WHERE created_at >= ? AND created_at < ?
                ");
                $stmt->bind_param("ss", $monthStart, $monthEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $userCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $userCount = (int)$row['count'];
                }
                
                // Get service count
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM services 
                    WHERE created_at >= ? AND created_at < ?
                ");
                $stmt->bind_param("ss", $monthStart, $monthEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $serviceCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $serviceCount = (int)$row['count'];
                }
                
                // Add to stats
                $stats['users']['by_month'][] = [
                    'month' => $monthLabel,
                    'count' => $userCount
                ];
                
                $stats['services']['by_month'][] = [
                    'month' => $monthLabel,
                    'count' => $serviceCount
                ];
            }
            
        } catch (Exception $e) {
            error_log("Error getting platform stats: " . $e->getMessage());
        }
        
        return $stats;
    }
}