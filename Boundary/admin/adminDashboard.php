<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Welcome top bar -->
<div class="topbar">
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
</div>

<!-- Logo section -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="#">Home</a>
    <a href="#">User Accounts</a>
    <a href="#">User Profiles</a>
</div>

<!-- Main content -->
<div class="dashboard-content">
    <h1>Admin Dashboard</h1>
    <p>This is the main control panel for Admin users.</p>
</div>

</body>
</html>
