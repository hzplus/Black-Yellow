<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

// Include controller
require_once '../../Controller/HomeownerController.php';
$homeownerController = new HomeownerController();

// Get date filters if present
$fromDate = isset($_GET['from']) ? $_GET['from'] : '';
$toDate = isset($_GET['to']) ? $_GET['to'] : '';

// Get service history
$serviceHistory = $homeownerController->getServiceHistory($_SESSION['userid'], $fromDate, $toDate);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Service History - Black&Yellow</title>
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
        <a href="homeownerDashboard.php">Home</a>
        <a href="searchCleaners.php">Find A Cleaner</a>
        <a href="shortlist.php">Shortlisted Cleaners</a>
        <a href="serviceHistory.php" class="active">Service History</a>
        <a href="myAccount.php">My Account</a>
    </div>
    
    <!-- Service History -->
    <div class="content-container">
        <h2>Service History</h2>
        
        <!-- Date Filter -->
        <div class="date-filter">
            <form action="serviceHistory.php" method="GET">
                <label for="from">From:</label>
                <input type="date" id="from" name="from" value="<?php echo $fromDate; ?>">
                
                <label for="to">To:</label>
                <input type="date" id="to" name="to" value="<?php echo $toDate; ?>">
                
                <button type="submit" class="filter-btn">Filter</button>
                <a href="serviceHistory.php" class="clear-btn">Clear</a>
            </form>
        </div>
        
        <?php if (empty($serviceHistory)): ?>
            <div class="no-results">
                <p>No service history found for the selected date range.</p>
            </div>
        <?php else: ?>
            <div class="service-history">
                <?php foreach ($serviceHistory as $service): ?>
                <div class="service-card">
                    <div class="cleaner-profile-pic">
                        <img src="../../assets/images/profile-placeholder.png" alt="Profile">
                    </div>
                    <div class="service-info">
                        <div class="service-header">
                            <h3>Service with <?php echo $service['cleaner_name']; ?></h3>
                            <span class="service-date"><?php echo date('M d, Y', strtotime($service['date'])); ?></span>
                        </div>
                        <p><strong>Services:</strong> <?php echo $service['service_name']; ?></p>
                        <p><strong>Price Paid:</strong> $<?php echo $service['price']; ?></p>
                        <p><strong>Summary:</strong> <?php echo $service['summary']; ?></p>
                        <div class="service-actions">
                            <a href="cleanerProfile.php?id=<?php echo $service['cleaner_id']; ?>" class="view-profile-btn">View Cleaner Profile</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>