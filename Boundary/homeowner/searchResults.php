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

// Process search parameters
$services = isset($_GET['services']) ? $_GET['services'] : [];
$day = isset($_GET['day']) ? $_GET['day'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : 0;
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Get search results
$cleaners = $homeownerController->searchCleaners($services, $day, $rating, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cleaner Search Results - Black&Yellow</title>
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
        <a href="searchCleaners.php" class="active">Find A Cleaner</a>
        <a href="shortlist.php">Shortlisted Cleaners</a>
        <a href="serviceHistory.php">Service History</a>
        <a href="myAccount.php">My Account</a>
    </div>
    
    <!-- Search Results -->
    <div class="content-container">
        <h2>Search Results</h2>
        <a href="searchCleaners.php" class="back-btn">Back to Search</a>
        
        <?php if (empty($cleaners)): ?>
            <div class="no-results">
                <p>No cleaners found matching your criteria. Please try different search parameters.</p>
            </div>
        <?php else: ?>
            <div class="search-results">
                <?php foreach ($cleaners as $cleaner): ?>
                <div class="cleaner-card">
                    <div class="cleaner-profile-pic">
                        <img src="../../assets/images/profile-placeholder.png" alt="Profile">
                    </div>
                    <div class="cleaner-info">
                        <h3><?php echo $cleaner['name']; ?></h3>
                        <div class="rating">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $cleaner['rating']) {
                                    echo '<span class="star filled">★</span>';
                                } else {
                                    echo '<span class="star">☆</span>';
                                }
                            }
                            ?>
                            <span class="rating-value">(<?php echo $cleaner['rating']; ?>)</span>
                        </div>
                        <p><strong>Price:</strong> $<?php echo $cleaner['price']; ?> per hour</p>
                        <p><strong>Availability:</strong> <?php echo $cleaner['availability']; ?></p>
                        <div class="cleaner-actions">
                            <a href="cleanerProfile.php?id=<?php echo $cleaner['id']; ?>" class="view-profile-btn">View Profile</a>
                            <?php if (!$homeownerController->isCleanerShortlisted($_SESSION['userid'], $cleaner['id'])): ?>
                                <form action="../../Controller/actions/addToShortlist.php" method="POST">
                                    <input type="hidden" name="cleaner_id" value="<?php echo $cleaner['id']; ?>">
                                    <button type="submit" class="shortlist-btn">Add to Shortlist</button>
                                </form>
                            <?php else: ?>
                                <button class="shortlist-btn disabled">Already Shortlisted</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>