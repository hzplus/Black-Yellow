<?php
// Start the session
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirect if no user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is a Homeowner, else redirect to login
$userRole = $_SESSION['role'];
if ($userRole != 'Homeowner') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homeowner Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<<<<<<< Updated upstream

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/header.php'; ?>

<div class="dashboard-container">
  <div class="dashboard-box">
    <h1 class="dashboard-title">Dashboard</h1>
    <div class="dashboard-options">
        <a href="CleanerListings.php" class="option-link">
            <div class="option">
                <h2>Find a Cleaner</h2>
                <p>Search for cleaners available in your area.</p>
            </div>
        </a>
        <a href="ShortlistedCleaners.php" class="option-link">
            <div class="option">
                <h2>Shortlisted Cleaners</h2>
                <p>View your saved and shortlisted cleaners.</p>
            </div>
        </a>
        <a href="ServiceHistory.php" class="option-link">
            <div class="option">
                <h2>Service History</h2>
                <p>Review past service bookings and feedback.</p>
            </div>
        </a>
=======
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
            <h1>Black&Yellow</h1>
        </div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($homeownerName); ?>!</span>
            <a href="../../login.php" class="logout-link">Logout</a>
        </div>
    </div>
    
    <!-- Navigation -->
    <div class="navbar">
        <a href="homeownerDashboard.php" class="active">Dashboard</a>
        <a href="BrowseCleaners.php">Find Cleaners</a>
        <a href="ShortlistedCleaners.php">Shortlisted</a>
        <a href="ServiceHistory.php">History</a>
        <a href="wMyAccount.php">My Account</a>
    </div>
    
    <div class="dashboard-content container">
        <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($homeownerName); ?></h1>
        <p class="dashboard-subtitle">Manage your cleaning services</p>
        
        <div class="dashboard-options">
            <a href="BrowseCleaners.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Find a Cleaner</h3>
                    <p>Search for cleaners available in your area.</p>
                </div>
            </a>
            
            <a href="ShortlistedCleaners.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h3>Shortlisted Cleaners</h3>
                    <p>View your saved and shortlisted cleaners.</p>
                </div>
            </a>
            
            <a href="ServiceHistory.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Service History</h3>
                    <p>Review past service bookings and feedback.</p>
                </div>
            </a>
            
            <a href="MyAccount.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>My Account</h3>
                    <p>View and update your account information.</p>
                </div>
            </a>
        </div>
>>>>>>> Stashed changes
    </div>
  </div>
</div>

</body>
</html>