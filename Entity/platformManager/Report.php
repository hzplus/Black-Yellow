<?php
// Entity/platformManager/Report.php
require_once __DIR__ . '/../../db/Database.php';

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
                'bookings_count' => 0,
                'active_categories_count' => 0
            ],
            'categories_usage' => [],
            'new_users' => [],
            'bookings' => []
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
        
        // Get new bookings count
        try {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM bookings 
                WHERE created_at BETWEEN ? AND ?
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $report['stats']['bookings_count'] = (int)$row['count'];
            }
            
            // Get bookings by category
            $stmt = $conn->prepare("
                SELECT s.category, COUNT(b.bookingid) as count 
                FROM bookings b
                JOIN services s ON b.serviceid = s.serviceid
                WHERE b.created_at BETWEEN ? AND ?
                GROUP BY s.category
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['bookings'][] = [
                    'category' => $row['category'] ? $row['category'] : 'Uncategorized',
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting daily booking stats: " . $e->getMessage());
        }
        
        // Get category usage
        try {
            $stmt = $conn->prepare("
                SELECT sc.name as category_name, COUNT(s.serviceid) as service_count
                FROM service_categories sc
                LEFT JOIN services s ON sc.name = s.category AND s.created_at BETWEEN ? AND ?
                GROUP BY sc.name
                ORDER BY service_count DESC
            ");
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['categories_usage'][] = [
                    'category_name' => $row['category_name'],
                    'service_count' => (int)$row['service_count']
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
            $stmt = $conn->prepare("
                SELECT sc.name as category_name, COUNT(s.serviceid) as service_count
                FROM service_categories sc
                LEFT JOIN services s ON sc.name = s.category AND s.created_at BETWEEN ? AND ?
                GROUP BY sc.name
                ORDER BY service_count DESC
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['categories_usage'][] = [
                    'category_name' => $row['category_name'],
                    'service_count' => (int)$row['service_count']
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
        
        // Get weekly breakdown
        try {
            // Determine the number of weeks in the month
            $firstDayOfMonth = new DateTime($startDate);
            $lastDayOfMonth = new DateTime($endDate);
            
            // First week: from 1st day of month to first Sunday
            $firstWeekEnd = clone $firstDayOfMonth;
            if ($firstDayOfMonth->format('w') != 0) { // If not Sunday
                $firstWeekEnd->modify('next Sunday');
            }
            
            // Array to store week info
            $weeks = [];
            
            // Add first week
            $weekStart = clone $firstDayOfMonth;
            $weekEnd = clone $firstWeekEnd;
            
            while ($weekStart <= $lastDayOfMonth) {
                // Ensure weekEnd does not exceed month end
                if ($weekEnd > $lastDayOfMonth) {
                    $weekEnd = clone $lastDayOfMonth;
                }
                
                $weekStartStr = $weekStart->format('Y-m-d');
                $weekEndStr = $weekEnd->format('Y-m-d');
                
                $weeks[] = [
                    'start' => $weekStartStr,
                    'end' => $weekEndStr,
                    'name' => 'Week ' . count($weeks) + 1 . ' (' . $weekStart->format('M d') . ' - ' . $weekEnd->format('M d') . ')'
                ];
                
                // Move to next week
                $weekStart->modify('+1 day');
                $weekStart->modify('this week Monday');
                $weekEnd = clone $weekStart;
                $weekEnd->modify('this week Sunday');
            }
            
            // Get data for each week
            foreach ($weeks as $week) {
                $weekStart = $week['start'] . ' 00:00:00';
                $weekEnd = $week['end'] . ' 23:59:59';
                
                // Get service count for the week
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM services 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $weekStart, $weekEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $serviceCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $serviceCount = (int)$row['count'];
                }
                
                // Get user count for the week
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM users 
                    WHERE created_at BETWEEN ? AND ?
                ");
                $stmt->bind_param("ss", $weekStart, $weekEnd);
                $stmt->execute();
                $result = $stmt->get_result();
                $userCount = 0;
                if ($row = $result->fetch_assoc()) {
                    $userCount = (int)$row['count'];
                }
                
                $report['weekly_breakdown'][] = [
                    'week_name' => $week['name'],
                    'new_services_count' => $serviceCount,
                    'new_users_count' => $userCount
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting monthly weekly breakdown: " . $e->getMessage());
        }
        
        // Get category usage for the month
        try {
            $stmt = $conn->prepare("
                SELECT sc.name as category_name, COUNT(s.serviceid) as service_count
                FROM service_categories sc
                LEFT JOIN services s ON sc.name = s.category AND s.created_at BETWEEN ? AND ?
                GROUP BY sc.name
                ORDER BY service_count DESC
            ");
            $stmt->bind_param("ss", $startTimestamp, $endTimestamp);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $report['categories_usage'][] = [
                    'category_name' => $row['category_name'],
                    'service_count' => (int)$row['service_count']
                ];
            }
            
            // Get top 5 categories
            $stmt = $conn->prepare("
                SELECT category, COUNT(*) as count 
                FROM services 
                WHERE created_at BETWEEN ? AND ? AND category IS NOT NULL AND category != ''
                GROUP BY category
                ORDER BY count DESC
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
            error_log("Error getting monthly category stats: " . $e->getMessage());
        }
        
        // Get new users by role for the month
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
        
        // Get new services by category for the month
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
     * Get dashboard summary data
     * 
     * @return array Dashboard summary data
     */
    public static function getDashboardSummary() {
        $conn = Database::getConnection();
        $summary = [
            'users' => [
                'total' => 0,
                'active' => 0,
                'by_role' => []
            ],
            'services' => [
                'total' => 0,
                'by_category' => []
            ],
            'categories' => [
                'total' => 0,
                'list' => []
            ],
            'activity' => [
                'recent_services' => [],
                'recent_users' => []
            ]
        ];
        
        // Get user counts
        try {
            // Total users
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            if ($row = $result->fetch_assoc()) {
                $summary['users']['total'] = (int)$row['count'];
            }
            
            // Active users
            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
            if ($row = $result->fetch_assoc()) {
                $summary['users']['active'] = (int)$row['count'];
            }
            
            // Users by role
            $result = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            while ($row = $result->fetch_assoc()) {
                $summary['users']['by_role'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting user counts: " . $e->getMessage());
        }
        
        // Get service counts
        try {
            // Total services
            $result = $conn->query("SELECT COUNT(*) as count FROM services");
            if ($row = $result->fetch_assoc()) {
                $summary['services']['total'] = (int)$row['count'];
            }
            
            // Services by category
            $result = $conn->query("SELECT category, COUNT(*) as count FROM services GROUP BY category");
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['category'])) {
                    $summary['services']['by_category'][] = [
                        'category' => $row['category'],
                        'count' => (int)$row['count']
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Error getting service counts: " . $e->getMessage());
        }
        
        // Get category info
        try {
            // Total categories
            $result = $conn->query("SELECT COUNT(*) as count FROM service_categories");
            if ($row = $result->fetch_assoc()) {
                $summary['categories']['total'] = (int)$row['count'];
            }
            
            // Category list
            $result = $conn->query("SELECT categoryid, name FROM service_categories ORDER BY name");
            while ($row = $result->fetch_assoc()) {
                $summary['categories']['list'][] = [
                    'id' => (int)$row['categoryid'],
                    'name' => $row['name']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting category info: " . $e->getMessage());
        }
        
        // Get recent activities
        try {
            // Recent services
            $result = $conn->query("
                SELECT s.serviceid, s.title, s.category, u.username as cleaner_name, s.created_at
                FROM services s
                JOIN users u ON s.cleanerid = u.userid
                ORDER BY s.created_at DESC 
                LIMIT 5
            ");
            
            while ($row = $result->fetch_assoc()) {
                $summary['activity']['recent_services'][] = [
                    'id' => (int)$row['serviceid'],
                    'title' => $row['title'],
                    'category' => $row['category'],
                    'cleaner_name' => $row['cleaner_name'],
                    'created_at' => $row['created_at']
                ];
            }
            
            // Recent users
            $result = $conn->query("
                SELECT userid, username, role, created_at
                FROM users
                ORDER BY created_at DESC
                LIMIT 5
            ");
            
            while ($row = $result->fetch_assoc()) {
                $summary['activity']['recent_users'][] = [
                    'id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'role' => $row['role'],
                    'created_at' => $row['created_at']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting recent activities: " . $e->getMessage());
        }
        
        return $summary;
    }
    
    /**
     * Get platform growth statistics
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
        
        // Get user stats
        try {
            // Total users
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            if ($row = $result->fetch_assoc()) {
                $stats['users']['total'] = (int)$row['count'];
            }
            
            // This month's users
            $currentMonth = date('Y-m-01');
            $nextMonth = date('Y-m-01', strtotime('+1 month'));
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM users 
                WHERE created_at >= ? AND created_at < ?
            ");
            
            $stmt->bind_param("ss", $currentMonth, $nextMonth);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $stats['users']['this_month'] = (int)$row['count'];
            }
            
            // Users by month (last 6 months)
            $months = [];
            for ($i = 0; $i < 6; $i++) {
                $month = date('Y-m-01', strtotime("-$i month"));
                $monthEnd = date('Y-m-01', strtotime("-" . ($i - 1) . " month"));
                $months[] = [
                    'start' => $month,
                    'end' => $monthEnd,
                    'label' => date('M Y', strtotime($month))
                ];
            }
            
            foreach ($months as $month) {
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM users 
                    WHERE created_at >= ? AND created_at < ?
                ");
                
                $stmt->bind_param("ss", $month['start'], $month['end']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $count = 0;
                if ($row = $result->fetch_assoc()) {
                    $count = (int)$row['count'];
                }
                
                $stats['users']['by_month'][] = [
                    'month' => $month['label'],
                    'count' => $count
                ];
            }
            
            // Users by role
            $result = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            while ($row = $result->fetch_assoc()) {
                $stats['users']['by_role'][] = [
                    'role' => $row['role'],
                    'count' => (int)$row['count']
                ];
            }
        } catch (Exception $e) {
            error_log("Error getting user stats: " . $e->getMessage());
        }
        
        // Get service stats
        try {
            // Total services
            $result = $conn->query("SELECT COUNT(*) as count FROM services");
            if ($row = $result->fetch_assoc()) {
                $stats['services']['total'] = (int)$row['count'];
            }
            
            // This month's services
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM services 
                WHERE created_at >= ? AND created_at < ?
            ");
            
            $stmt->bind_param("ss", $currentMonth, $nextMonth);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $stats['services']['this_month'] = (int)$row['count'];
            }
            
            // Services by month (last 6 months)
            foreach ($months as $month) {
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM services 
                    WHERE created_at >= ? AND created_at < ?
                ");
                
                $stmt->bind_param("ss", $month['start'], $month['end']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $count = 0;
                if ($row = $result->fetch_assoc()) {
                    $count = (int)$row['count'];
                }
                
                $stats['services']['by_month'][] = [
                    'month' => $month['label'],
                    'count' => $count
                ];
            }
            
            // Services by category
            $result = $conn->query("SELECT category, COUNT(*) as count FROM services GROUP BY category");
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['category'])) {
                    $stats['services']['by_category'][] = [
                        'category' => $row['category'],
                        'count' => (int)$row['count']
                    ];
                }
            }
            
            // Sort by count
            usort($stats['services']['by_category'], function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            // Get most popular categories (top 5)
            $stats['popular_categories'] = array_slice($stats['services']['by_category'], 0, 5);
            
        } catch (Exception $e) {
            error_log("Error getting service stats: " . $e->getMessage());
        }
        
        return $stats;
    }
}