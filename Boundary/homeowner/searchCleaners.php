<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Homeowner Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <img src="../../assets/images/logo.jpg" alt="Logo">
        </div>
        <div class="search-container">
            <form action="searchResults.php" method="GET">
                <input type="text" name="query" placeholder="Search for cleaners...">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="user-info">
            <span>Welcome Homeowner: <?php echo $_SESSION['username']; ?>!</span>
            <a href="../../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="homeownerDashboard.php" class="active">Home</a>
        <a href="searchCleaners.php">Find A Cleaner</a>
        <a href="shortlist.php">Shortlisted Cleaners</a>
        <a href="serviceHistory.php">Service History</a>
        <a href="myAccount.php">My Account</a>
    </div>
    
    <!-- Dashboard Content -->
    <div class="dashboard">
        <h1>Homeowner Dashboard</h1>
        <p>Welcome to your dashboard. Here you can search for cleaners, manage your shortlisted cleaners, view your service history, and update your account information.</p>
        
        <div class="dashboard-shortcuts">
            <div class="shortcut-card">
                <h3>Find a Cleaner</h3>
                <p>Search for cleaners based on services, availability, and ratings.</p>
                <a href="searchCleaners.php" class="btn">Search Now</a>
            </div>
            
            <div class="shortcut-card">
                <h3>Shortlisted Cleaners</h3>
                <p>View and manage your shortlisted cleaners.</p>
                <a href="shortlist.php" class="btn">View Shortlist</a>
            </div>
            
            <div class="shortcut-card">
                <h3>Service History</h3>
                <p>View your past cleaning services and history.</p>
                <a href="serviceHistory.php" class="btn">View History</a>
            </div>
            
            <div class="shortcut-card">
                <h3>My Account</h3>
                <p>Update your personal information and password.</p>
                <a href="myAccount.php" class="btn">Update Info</a>
            </div>
        </div>
    </div>
</body>
</html>