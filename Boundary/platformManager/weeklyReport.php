<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../Controller/platformManager/weeklyReportController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new WeeklyReportController();

// Default to current week
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Generate the report
$report = $controller->generateReport($selectedDate);

// Calculate totals
$totalNewServices = 0;
foreach ($report['new_services'] as $service) {
    $totalNewServices += $service['count'];
}

$totalNewUsers = 0;
foreach ($report['new_users'] as $user) {
    $totalNewUsers += $user['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weekly Report</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styles for enhanced reports */
        .report-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .report-title {
            font-size: 28px;
            font-weight: bold;
            color: #FFD700;
            display: flex;
            align-items: center;
            gap: 10px;
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
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .report-nav a:hover {
            background-color: rgba(255, 215, 0, 0.1);
            border-color: #FFD700;
            transform: translateY(-2px);
        }
        
        .report-nav a.active {
            background-color: rgba(255, 215, 0, 0.15);
            color: #FFD700;
            border-color: #FFD700;
        }
        
        .date-selector {
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #444;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .date-selector form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            width: 100%;
        }
        
        .date-selector label {
            color: #FFD700;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .date-selector input[type="date"] {
            padding: 10px;
            background-color: #252525;
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
            display: flex;
            align-items: center;
            gap: 5px;
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
            border: 1px solid #444;
            border-radius: 8px;
            padding: 25px 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #FFD700;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #FFD700;
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
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 16px;
            color: #e0e0e0;
        }
        
        .report-section {
            background-color: #1a1a1a;
            border: 1px solid #444;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .table-container {
            padding: 5px;
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
            display: flex;
            align-items: center;
            gap: 8px;
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
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .weekly-badge {
            background-color: #3498db;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            margin-left: 10px;
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
            
            .report-title {
                color: black;
                text-align: center;
                margin-bottom: 30px;
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
            
            .data-table tr:hover td {
                background-color: transparent;
            }
            
            .empty-data {
                color: #777;
            }
            
            .weekly-badge {
                background-color: #f5f5f5;
                color: black;
                border: 1px solid #ddd;
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
        <h1 class="report-title">
            <i class="fas fa-calendar-week"></i> Weekly Report - <?= date('F j', strtotime($report['start_date'])) ?> to <?= date('F j, Y', strtotime($report['end_date'])) ?>
        </h1>
        
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
                <i class="fas fa-calendar-alt"></i> Select Week (any date in the week):
            </label>
            <input type="date" id="date" name="date" value="<?= $selectedDate ?>" max="<?= date('Y-m-d') ?>">
            <button type="submit">
                <i class="fas fa-sync-alt"></i> Generate Report
            </button>
        </form>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <div class="stat-value"><?= $totalNewServices ?></div>
            <div class="stat-label">New Services</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-value"><?= $totalNewUsers ?></div>
            <div class="stat-label">New Users</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-th-list"></i>
            </div>
            <div class="stat-value"><?= count($report['categories_usage']) ?></div>
            <div class="stat-label">Active Categories</div>
        </div>
    </div>
    
    <!-- Daily Breakdown Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-chart-bar"></i> Daily Activity
        </div>
        <div class="table-container">
            <?php if (empty($report['daily_breakdown'])): ?>
                <div class="empty-data">No data available for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>New Services</th>
                            <th>New Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['daily_breakdown'] as $day): ?>
                            <tr>
                                <td><?= date('l, F j, Y', strtotime($day['date'])) ?></td>
                                <td><?= $day['new_services_count'] ?? 0 ?></td>
                                <td><?= $day['new_users_count'] ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Category Usage Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-th-list"></i> Service Category Usage
        </div>
        <div class="table-container">
            <?php if (empty($report['categories_usage'])): ?>
                <div class="empty-data">No category usage data available for this week</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Service Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['categories_usage'] as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                <td><?= $category['service_count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- New Users Section -->
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
                            <th>Number of Registrations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['new_users'] as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><?= $user['count'] ?></td>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['new_services'] as $service): ?>
                            <tr>
                                <td><?= htmlspecialchars($service['category']) ?></td>
                                <td><?= $service['count'] ?></td>
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

</body>
</html>