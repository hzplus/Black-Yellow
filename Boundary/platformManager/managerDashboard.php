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
</head>
<body>

<!-- Topbar with logo and welcome message -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Logo">
    </div>
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<!-- Dashboard content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Platform Manager Dashboard</h1>
        <p style="text-align: center; margin-bottom: 30px;">This is the main control panel for Platform Manager users.</p>
        
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