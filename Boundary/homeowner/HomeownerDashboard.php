<?php
// Start the session
session_start();

// Redirect if no user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit();
}

// Check if the user is a Homeowner, else redirect to login
$userRole = $_SESSION['role'];
if ($userRole != 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Use session data instead of database queries
$homeownerName = $_SESSION['username'] ?? "Homeowner";

// Sample mock data instead of using controllers
$recentServices = [
    [
        'serviceId' => 1,
        'serviceTitle' => 'Regular House Cleaning',
        'cleanerName' => 'John Cleaner',
        'confirmedAt' => '2025-04-10 09:00:00'
    ],
    [
        'serviceId' => 2,
        'serviceTitle' => 'Deep Cleaning Service',
        'cleanerName' => 'Mary Clean',
        'confirmedAt' => '2025-03-22 14:30:00'
    ],
    [
        'serviceId' => 3,
        'serviceTitle' => 'Window Cleaning',
        'cleanerName' => 'Bob Shine',
        'confirmedAt' => '2025-03-15 10:15:00'
    ]
];

// Helper function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('F j, Y');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homeowner Dashboard - Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
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
        <a href="ViewCleanerListings.php">Find Cleaners</a>
        <a href="ViewShortlistedCleaners.php">Shortlisted</a>
        <a href="ViewServiceHistory.php">History</a>
        <a href="ViewMyAccount.php">My Account</a>
    </div>
    
    <div class="dashboard-content container">
        <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($homeownerName); ?></h1>
        <p class="dashboard-subtitle">Manage your cleaning services</p>
        
        <div class="dashboard-options">
            <a href="ViewCleanerListings.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Find a Cleaner</h3>
                    <p>Search for cleaners available in your area.</p>
                </div>
            </a>
            
            <a href="ViewShortlistedCleaners.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h3>Shortlisted Cleaners</h3>
                    <p>View your saved and shortlisted cleaners.</p>
                </div>
            </a>
            
            <a href="ViewServiceHistory.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Service History</h3>
                    <p>Review past service bookings and feedback.</p>
                </div>
            </a>
            
            <a href="ViewMyAccount.php" class="option-link">
                <div class="option-card">
                    <div class="icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>My Account</h3>
                    <p>View and update your account information.</p>
                </div>
            </a>
        </div>
    </div>
</body>
</html>