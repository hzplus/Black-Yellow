<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../Controller/platformManager/dailyReportController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new DailyReportController();

// Default to today's date
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Generate the report
$report = $controller->generateReport($selectedDate);

// Calculate totals
$totalBookings = 0;
foreach ($report['bookings'] as $booking) {
    $totalBookings += $booking['count'];
}

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report | Service Platform</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom styles for enhanced reports */
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
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
            background-color: #222;
            color: #FFD700;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            border: 1px solid #FFD700;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .report-nav a:hover {
            background-color: #FFD700;
            color: #000;
        }
        
        .report-nav a.active {
            background-color: #FFD700;
            color: #000;
        }
        
        .date-selector {
            background-color: #222;
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
            background-color: #111;
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
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #111;
            border: 1px solid #333;
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
            color: #ccc;
        }
        
        .report-section {
            background-color: #111;
            border: 1px solid #333;
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #222;
            padding: 15px 20px;
            color: #FFD700;
            font-weight: bold;
            font-size: 18px;
            border-bottom: 1px solid #333;
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
            background-color: #222;
            color: #FFD700;
            text-align: left;
            padding: 12px 15px;
            font-weight: bold;
            border-bottom: 1px solid #444;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #333;
            color: #fff;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover td {
            background-color: #1a1a1a;
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
        }
        
        .btn-back {
            background-color: #222;
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
            background-color: #333;
        }
        
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
        }
        
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

<div class="topbar">
    <div>
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    </div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<div class="report-container">
    <div class="report-header">
        <h1 class="report-title">
            <i class="fas fa-calendar-day"></i> Daily Report - <?= date('F j, Y', strtotime($selectedDate)) ?>
        </h1>
        
        <div class="report-nav">
            <a href="dailyReport.php" class="active">
                <i class="fas fa-calendar-day"></i> Daily
            </a>
            <a href="weeklyReport.php">
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
                <i class="fas fa-calendar-alt"></i> Select Date:
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
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value"><?= $totalBookings ?></div>
            <div class="stat-label">Total Bookings</div>
        </div>
        
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
    </div>
    
    <!-- Service Category Usage Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-th-list"></i> Service Category Usage
        </div>
        <div class="table-container">
            <?php if (empty($report['categories_usage'])): ?>
                <div class="empty-data">No category usage data available for this date</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Service Count</th>
                            <th>Booking Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['categories_usage'] as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                <td><?= $category['service_count'] ?></td>
                                <td><?= $category['booking_count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bookings Section -->
    <div class="report-section">
        <div class="section-header">
            <i class="fas fa-calendar-check"></i> Bookings by Category
        </div>
        <div class="table-container">
            <?php if (empty($report['bookings']) || array_sum(array_column($report['bookings'], 'count')) == 0): ?>
                <div class="empty-data">No bookings data available for this date</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Number of Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['bookings'] as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['category']) ?></td>
                                <td><?= $booking['count'] ?></td>
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
                <div class="empty-data">No new user registrations for this date</div>
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
                <div class="empty-data">No new services created for this date</div>
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