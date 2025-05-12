<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports Menu - Platform Manager</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Logo">
    </div>
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<!-- Main content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Reports Management</h1>
        <p style="text-align: center; margin-bottom: 30px;">Select a report to generate:</p>

        <div class="dashboard-options">
            <a href="dailyReport.php" class="option-link">
                <div class="option">
                    <h2>Daily Report</h2>
                    <p>Generate a report for daily performance and activity.</p>
                </div>
            </a>
            
            <a href="weeklyReport.php" class="option-link">
                <div class="option">
                    <h2>Weekly Report</h2>
                    <p>Generate a report to analyze weekly trends and make adjustments.</p>
                </div>
            </a>
            
            <a href="monthlyReport.php" class="option-link">
                <div class="option">
                    <h2>Monthly Report</h2>
                    <p>Generate a report to evaluate long-term service category usage and performance.</p>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>