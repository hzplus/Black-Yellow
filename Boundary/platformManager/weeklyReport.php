<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../Entity/platformManager/Report.php';

// Check user access
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

// Default to current date
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Generate the report
$report = Report::getWeeklyReport($selectedDate);

// Get platform statistics for total counts - force refresh
$platformStats =    Report::getPlatformStats();

// Calculate totals for this week
$totalNewUsers = 0;
$totalNewServices = 0;
foreach ($report['daily_breakdown'] as $day) {
    $totalNewUsers += $day['new_users_count'];
    $totalNewServices += $day['new_services_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report | Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Simplified styles for enhanced reports */
        .report-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .report-header {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #333;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .report-title {
            font-size: 26px;
            font-weight: bold;
            color: #FFD700;
            margin: 0;
        }
        
        .report-date {
            font-size: 18px;
            color: #e0e0e0;
            margin-top: 5px;
        }
        
        .report-nav {
            display: flex;
            gap: 10px;
        }
        
        .report-nav a {
            background-color: #252525;
            color: #FFD700;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid #444;
            transition: all 0.3s ease;
        }
        
        .report-nav a:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .report-nav a.active {
            background-color: rgba(255, 215, 0, 0.15);
            color: #FFD700;
            border-color: #FFD700;
        }
        
        .date-selector {
            background-color: #252525;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #444;
        }
        
        .date-selector form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .date-selector label {
            color: #FFD700;
            font-weight: bold;
        }
        
        .date-selector input[type="date"] {
            padding: 10px;
            background-color: #1a1a1a;
            border: 1px solid #444;
            color: #fff;
            border-radius: 4px;
        }
        
        .date-selector button {
            background-color: #FFD700;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .date-selector button:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 25px 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #FFD700;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .stat-icon {
            font-size: 32px;
            color: #FFD700;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #FFD700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 16px;
            color: #e0e0e0;
        }
        
        .stat-total {
            font-size: 14px;
            color: #aaa;
            margin-top: 5px;
        }
        
        .report-section {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #252525;
            padding: 15px 20px;
            color: #FFD700;
            font-weight: bold;
            font-size: 18px;
            border-bottom: 1px solid #444;
        }
        
        .table-container {
            padding: 20px;
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: #252525;
            color: #FFD700;
            text-align: left;
            padding: 12px 15px;
            font-weight: bold;
            border-bottom: 1px solid #444;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #333;
            color: #e0e0e0;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover td {
            background-color: #252525;
        }
        
        .empty-data {
            padding: 25px;
            text-align: center;
            color: #888;
            font-style: italic;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        .btn-print {
            background-color: #FFD700;
            color: #000;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-print:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background-color: #252525;
            color: #FFD700;
            border: 1px solid #FFD700;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-2px);
        }
        
        /* Print Styles */
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            
            .topbar, .navbar, .logo, .date-selector, .report-nav, .action-buttons {
                display: none !important;
            }
            
            .report-container {
                padding: 0;
                margin: 0;
            }
            
            .report-header {
                background-color: white;
                border: none;
            }
            
            .report-title {
                color: black;
                text-align: center;
                margin-bottom: 30px;
            }
            
            .report-date {
                color: #555;
            }
            
            .stat-card {
                background-color: white;
                border: 1px solid #ddd;
                break-inside: avoid;
            }
            
            .stat-value {
                color: black;
            }
            
            .stat-label, .stat-icon {
                color: #555;
            }
            
            .report-section {
                background-color: white;
                border: 1px solid #ddd;
                break-inside: avoid;
                margin-bottom: 15px;
            }
            
            .section-header {
                background-color: #f5f5f5;
                color: black;
                border-bottom: 1px solid #ddd;
            }
            
            .data-table th {
                background-color: #f5f5f5;
                color: black;
                border-bottom: 1px solid #ddd;
            }
            
            .data-table td {
                color: black;
                border-bottom: 1px solid #ddd;
            }
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .report-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .date-selector form {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<div class="report-container">
    <div class="report-header">
        <div>
            <h1 class="report-title">
                <i class="fas fa-calendar-week"></i> Weekly Report
            </h1>
            <div class="report-date">
                <?= date('F j', strtotime($report['start_date'])) ?> to <?= date('F j, Y', strtotime($report['end_date'])) ?>
            </div>
        </div>
        
        <div class="report-nav">
            <a href="dailyReport.php">
                <i class="fas fa-calendar-day"></i> Daily
            </a>
            <a href="weeklyReport.php" class="active">
                <i class="fas fa-calendar-week"></i> Weekly
            </a>
            <a href="monthlyReport.php">
                <i class="fas fa-calendar-alt"></i> Monthly
            </a>
        </div>
    </div>
    
    <div class="date-selector">
        <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
            <label for="date">
                <i class="fas fa-calendar-alt"></i> Select Date (any day in the week):
            </label>
            <input type="date" id="date" name="date" value="<?= $selectedDate ?>" max="<?= date('Y-m-d') ?>">
            <button type="submit">
                <i class="fas fa-sync-alt"></i> Generate Report
            </button>
            <!-- Add a hidden timestamp to force cache refresh -->
            <input type="hidden" name="cache_buster" value="<?= time() ?>">
        </form>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?= $totalNewUsers ?></div>
            <div class="stat-label">New Users This Week</div>
            <div class="stat-total">Total: <?= $platformStats['users']['total'] ?></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <div class="stat-value"><?= $totalNewServices ?></div>
            <div class="stat-label">New Services This Week</div>
            <div class="stat-total">Total: <?= $platformStats['services']['total'] ?></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-th-list"></i>
            </div>
            <div class="stat-value"><?= count($report['categories_usage']) ?></div>
            <div class="stat-label">Active Categories</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value"><?= count($report['daily_breakdown']) ?></div>
            <div class="stat-label">Days in Review</div>
        </div>
    </div>
    
    <!-- Daily Breakdown Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i> Daily Activity Breakdown
        </div>
        <div class="table-container">
            <?php if (empty($report['daily_breakdown'])): ?>
                <div class="empty-data">No data available for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>New Users</th>
                            <th>New Services</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['daily_breakdown'] as $day): ?>
                            <tr>
                                <td><?= date('l, F j, Y', strtotime($day['date'])) ?></td>
                                <td><?= $day['new_users_count'] ?? 0 ?></td>
                                <td><?= $day['new_services_count'] ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- User Registration Breakdown -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-user-plus"></i> New User Registrations
        </div>
        <div class="table-container">
            <?php if (empty($report['new_users'])): ?>
                <div class="empty-data">No new user registrations for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User Role</th>
                            <th>New Registrations</th>
                            <th>Total Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($report['new_users'] as $user): 
                            // Find total for this role
                            $totalForRole = 0;
                            foreach ($platformStats['users']['by_role'] as $roleData) {
                                if ($roleData['role'] === $user['role']) {
                                    $totalForRole = $roleData['count'];
                                    break;
                                }
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><?= $user['count'] ?></td>
                                <td><?= $totalForRole ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Service Category Usage Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-th-list"></i> Service Category Activity
        </div>
        <div class="table-container">
            <?php if (empty($report['categories_usage'])): ?>
                <div class="empty-data">No category usage data available for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>New Services</th>
                            <th>Total Services</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($report['categories_usage'] as $category):
                            // Find total services for this category
                            $totalForCategory = 0;
                            foreach ($platformStats['services']['by_category'] as $catData) {
                                if ($catData['category'] === $category['category_name']) {
                                    $totalForCategory = $catData['count'];
                                    break;
                                }
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                <td><?= $category['service_count'] ?></td>
                                <td><?= $totalForCategory ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- New Services Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-concierge-bell"></i> New Services Created
        </div>
        <div class="table-container">
            <?php if (empty($report['new_services'])): ?>
                <div class="empty-data">No new services created for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Number of Services</th>
                            <th>% of Weekly Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['new_services'] as $service): ?>
                            <tr>
                                <td><?= htmlspecialchars($service['category']) ?></td>
                                <td><?= $service['count'] ?></td>
                                <td><?= $totalNewServices > 0 ? round(($service['count'] / $totalNewServices) * 100) : 0 ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="action-buttons">
        <button onclick="window.print();" class="btn-print">
            <i class="fas fa-print"></i> Print Report
        </button>
        <a href="reportsMenu.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>
</div>

<script>
// Add time parameter to prevent caching when generating reports
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.date-selector form');
    form.addEventListener('submit', function(e) {
        // Add a timestamp to prevent caching
        const hiddenInput = document.querySelector('input[name="cache_buster"]');
        if (hiddenInput) {
            hiddenInput.value = new Date().getTime();
        } else {
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'cache_buster';
            newInput.value = new Date().getTime();
            this.appendChild(newInput);
        }
    });
});
</script>

</body>
</html>