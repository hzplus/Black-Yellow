<?php
// Boundary/homeowner/HomeownerDashboard.php

// Start the session
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Include the Database connection
require_once(__DIR__ . '/../../db/Database.php');

// Get database connection
$conn = Database::getConnection();

// Get homeowner information
$homeownerId = $_SESSION['userid'];
$homeownerName = "";

$stmt = $conn->prepare("SELECT username FROM users WHERE userid = ? AND role = 'Homeowner'");
$stmt->bind_param("i", $homeownerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $homeownerName = $row['username'];
}
$stmt->close();

// Get recent service history
$recentServices = [];
$stmt = $conn->prepare("
    SELECT cm.matchid, cm.serviceid, cm.cleanerid, cm.confirmed_at,
           s.title as service_title,
           u.username as cleaner_name
    FROM confirmed_matches cm
    JOIN services s ON cm.serviceid = s.serviceid
    JOIN users u ON cm.cleanerid = u.userid
    WHERE cm.homeownerid = ?
    ORDER BY cm.confirmed_at DESC
    LIMIT 3
");
$stmt->bind_param("i", $homeownerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $service = [
            'serviceId' => $row['serviceid'],
            'serviceTitle' => $row['service_title'],
            'cleanerName' => $row['cleaner_name'],
            'confirmedAt' => $row['confirmed_at']
        ];
        $recentServices[] = $service;
    }
}
$stmt->close();

// Get shortlisted cleaners count
$shortlistedCount = 0;
$stmt = $conn->prepare("
    SELECT COUNT(*) as count
    FROM shortlists
    WHERE homeownerid = ?
");
$stmt->bind_param("i", $homeownerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $shortlistedCount = $row['count'];
}
$stmt->close();

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
    <title>Homeowner Dashboard - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Include the header (topbar and navbar) -->
    <?php include '../../assets/includes/header.php'; ?>
    
    <div class="dashboard-container">
        <div class="dashboard-box">
            <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($homeownerName); ?></h1>            
            <div class="dashboard-options">
                <a href="ViewCleanerListings.php" class="option-link">
                    <div class="option">
                        <h2>Find a Cleaner</h2>
                        <p>Search for cleaners available in your area.</p>
                    </div>
                </a>
                <a href="ViewShortlistedCleaners.php" class="option-link">
                    <div class="option">
                        <h2>Shortlisted Cleaners</h2>
                        <p>View your saved and shortlisted cleaners.</p>
                    </div>
                </a>
                <a href="ViewServiceHistory.php" class="option-link">
                    <div class="option">
                        <h2>Service History</h2>
                        <p>Review past service bookings and feedback.</p>
                    </div>
                </a>
                <a href="ViewMyAccount.php" class="option-link">
                    <div class="option">
                        <h2>My Account</h2>
                        <p>View and update your account information.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>