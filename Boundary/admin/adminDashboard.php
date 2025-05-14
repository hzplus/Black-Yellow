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
    <title>Admin Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">


</head>
<body>

<!-- Topbar with logo and welcome message -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </div>
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
    <a href="../../logout.php">Logout</a>
</div>

<!-- Dashboard content -->
<div class="dashboard-content">
    <h1>Admin Dashboard</h1>
    <p>This is the main control panel for Admin users.</p>
</div>

</body>
</html>
