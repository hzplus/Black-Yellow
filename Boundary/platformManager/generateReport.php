<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'PlatformManager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Report - Platform Manager</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Top bar -->
<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="platformManagerDashboard.php">Home</a>
    <a href="platformManagerMenu.php">Service Categories</a>
    <a href="generateReport.php">Reports</a>
</div>

<!-- Main content -->
<div class="dashboard-content">
    <h1>Generate Service Category Report</h1>
    <p>Select a report type to view system performance:</p>

    <div class="dashboard-options">
        <button onclick="location.href='generateDailyReport.php'">📅 Daily Report</button>
        <button onclick="location.href='generateWeeklyReport.php'">📈 Weekly Report</button>
        <button onclick="location.href='generateMonthlyReport.php'">📊 Monthly Report</button>
    </div>
</div>

</body>
</html>
