<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cleaner Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css"> <!-- adjust path as needed -->
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo" class="logo-img">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<!-- Navbar -->
<div class="navbar">
    <a href="#">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="#">Jobs History</a>
    <a href="../../logout.php">Logout</a>
</div>

<!-- Dashboard content -->
<div class="dashboard">
    <h1>Cleaner Dashboard</h1>
    <p>This is your central hub to manage services and jobs.</p>
</div>

</body>
</html>
