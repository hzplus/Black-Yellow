<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
    <img src="../../assets/images/logo.jpg" alt="Logo" class="logo">
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </div>
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="platformManagerDashboard.php">Home</a>
    <a href="platformManagerMenu.php">Service Category</a>
    <a href="generateReport.php">Reports</a>
    <a href="../../logout.php">Logout</a>
</div>

<!-- Dashboard content -->
<div class="dashboard-content">
    <h1>Platform Mangaer Dashboard</h1>
    <p>This is the main control panel for Platform manager.</p>
</div>

</body>
</html>
