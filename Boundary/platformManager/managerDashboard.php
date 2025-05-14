<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Platform Manager Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .dashboard-box {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            color: #FFD700;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }
        
        .dashboard-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .option-link {
            text-decoration: none;
        }
        
        .option {
            background-color: #252525;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 25px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
        }
        
        .option:hover {
            transform: translateY(-5px);
            border-color: #FFD700;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
        }
        
        .option h2 {
            color: #FFD700;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .option p {
            color: #e0e0e0;
            text-align: center;
            margin: 0;
        }
        
        .option:before {
            content: '';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 48px;
            color: #FFD700;
            margin-bottom: 20px;
        }
        
        .option:nth-child(1):before { content: '\f0db'; } /* Categories */
        .option:nth-child(2):before { content: '\f080'; } /* Reports */
        
        .welcome-banner {
            background: linear-gradient(135deg, #252525 0%, #1A1A1A 100%);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner:before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 215, 0, 0.05);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            z-index: 0;
        }
        
        .welcome-banner h1 {
            color: #FFD700;
            font-size: 32px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-banner p {
            color: #e0e0e0;
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background-color: #252525;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border: 1px solid #444;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            border-color: #FFD700;
            transform: translateY(-5px);
        }
        
        .stat-card i {
            font-size: 30px;
            color: #FFD700;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #FFD700;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #e0e0e0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<!-- Dashboard content -->
<div class="dashboard-container">
    <div class="welcome-banner">
        <h1>Platform Manager Dashboard</h1>
        <p>Welcome to your control center. Manage service categories and generate reports to keep the platform running smoothly.</p>
    </div>
    
    <!-- Stats Summary Row -->
    <div class="stats-row">
        <div class="stat-card">
            <i class="fas fa-th-list"></i>
            <div class="stat-number">12</div>
            <div class="stat-label">Service Categories</div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <div class="stat-number">42</div>
            <div class="stat-label">Active Users</div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <div class="stat-number">18</div>
            <div class="stat-label">Today's Bookings</div>
        </div>
        
        <div class="stat-card">
            <i class="fas fa-concierge-bell"></i>
            <div class="stat-number">156</div>
            <div class="stat-label">Total Services</div>
        </div>
    </div>
    
    <div class="dashboard-box">
        <h1 class="dashboard-title">Management Options</h1>
        <p style="text-align: center; margin-bottom: 30px; color: #e0e0e0;">This is the main control panel for Platform Manager users.</p>
        
        <div class="dashboard-options">
            <a href="categoriesMenu.php" class="option-link">
                <div class="option">
                    <h2>Service Categories</h2>
                    <p>Manage your service categories: create, view, update, and delete categories.</p>
                </div>
            </a>
            
            <a href="reportsMenu.php" class="option-link">
                <div class="option">
                    <h2>Reports</h2>
                    <p>Generate and view reports: daily, weekly, and monthly analytics.</p>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>