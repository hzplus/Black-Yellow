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
    </div>
  </div>
</div>

</body>
</html>