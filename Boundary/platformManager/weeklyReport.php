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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weekly Report</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .report-section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .report-section h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .data-table th {
            background-color: #f5f5f5;
        }
        .date-picker {
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            font-size: 1em;
        }
        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            margin: 10px 0;
        }
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media print {
            .no-print {
                display: none;
            }
            .navbar, .topbar, .logo {
                display: none;
            }
            .dashboard-content {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="topbar no-print">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<div class="logo no-print">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<div class="navbar no-print">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<div class="dashboard-content">
    <h1>Weekly Report - <?= date('F j', strtotime($report['start_date'])) ?> to <?= date('F j, Y', strtotime($report['end_date'])) ?></h1>
    
    <div class="date-picker no-print">
        <form method="GET">
            <label for="date">Select Week (any date in the week):</label>
            <input type="date" id="date" name="date" value="<?= $selectedDate ?>" max="<?= date('Y-m-d') ?>">
            <button type="submit">Generate Report</button>
        </form>
    </div>
    
    <div class="stats-grid">
        <?php
        // Calculate total bookings
        $totalBookings = 0;
        foreach ($report['bookings'] as $booking) {
            $totalBookings += $booking['count'];
        }
        
        // Calculate total new services
        $totalNewServices = 0;
        foreach ($report['new_services'] as $service) {
            $totalNewServices += $service['count'];
        }
        
        // Calculate total new users
        $totalNewUsers = 0;
        foreach ($report['new_users'] as $user) {
            $totalNewUsers += $user['count'];
        }
        ?>
        
        <div class="stat-card">
            <h3>Total Bookings</h3>
            <div class="number"><?= $totalBookings ?></div>
        </div>
        
        <div class="stat-card">
            <h3>New Services</h3>
            <div class="number"><?= $totalNewServices ?></div>
        </div>
        
        <div class="stat-card">
            <h3>New Users</h3>
            <div class="number"><?= $totalNewUsers ?></div>
        </div>
    </div>
    
    <!-- Daily Breakdown Section -->
    <div class="report-section">
        <h2>Daily Booking Activity</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Number of Bookings</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['daily_breakdown'])): ?>
                    <tr>
                        <td colspan="2">No booking data available for this week</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['daily_breakdown'] as $day): ?>
                        <tr>
                            <td><?= date('l, F j, Y', strtotime($day['date'])) ?></td>
                            <td><?= $day['booking_count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Category Usage Section -->
    <div class="report-section">
        <h2>Service Category Usage</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Service Count</th>
                    <th>Booking Count</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['categories_usage'])): ?>
                    <tr>
                        <td colspan="3">No category usage data available for this week</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['categories_usage'] as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['category_name']) ?></td>
                            <td><?= $category['service_count'] ?></td>
                            <td><?= $category['booking_count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Bookings Section -->
    <div class="report-section">
        <h2>Bookings by Category</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Number of Bookings</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['bookings'])): ?>
                    <tr>
                        <td colspan="2">No bookings data available for this week</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['bookings'] as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['category']) ?></td>
                            <td><?= $booking['count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- New Users Section -->
    <div class="report-section">
        <h2>New User Registrations</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Role</th>
                    <th>Number of Registrations</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['new_users'])): ?>
                    <tr>
                        <td colspan="2">No new user registrations for this week</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['new_users'] as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= $user['count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- New Services Section -->
    <div class="report-section">
        <h2>New Services Created</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Number of Services</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['new_services'])): ?>
                    <tr>
                        <td colspan="2">No new services created for this week</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['new_services'] as $service): ?>
                        <tr>
                            <td><?= htmlspecialchars($service['category']) ?></td>
                            <td><?= $service['count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="no-print">
        <button onclick="window.print();" class="print-button">Print Report</button>
        <a href="reportsMenu.php" class="button">Back to Reports</a>
    </div>
</div>

</body>
</html>