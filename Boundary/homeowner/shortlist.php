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

// Get shortlisted cleaners
$shortlistedCleaners = $homeownerController->getShortlistedCleaners($_SESSION['userid']);

// Handle search within shortlist
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$searchType = isset($_GET['searchType']) ? $_GET['searchType'] : 'name';

if (!empty($searchQuery)) {
    $shortlistedCleaners = $homeownerController->searchShortlistedCleaners($_SESSION['userid'], $searchQuery, $searchType);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shortlisted Cleaners - Black&Yellow</title>
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
        <a href="shortlist.php" class="active">Shortlisted Cleaners</a>
        <a href="serviceHistory.php">Service History</a>
        <a href="myAccount.php">My Account</a>
    </div>
    
    <!-- Shortlisted Cleaners -->
    <div class="content-container">
        <h2>Shortlisted Cleaners</h2>
        
        <!-- Search within shortlist -->
        <div class="shortlist-search">
            <form action="shortlist.php" method="GET">
                <input type="text" name="search" placeholder="Search in shortlist..." value="<?php echo $searchQuery; ?>">
                <div class="search-type">
                    <label>
                        <input type="radio" name="searchType" value="name" <?php echo ($searchType == 'name') ? 'checked' : ''; ?>>
                        Name
                    </label>
                    <label>
                        <input type="radio" name="searchType" value="service" <?php echo ($searchType == 'service') ? 'checked' : ''; ?>>
                        Service
                    </label>
                </div>
                <button type="submit">Search</button>
            </form>
        </div>
        
        <?php if (empty($shortlistedCleaners)): ?>
            <div class="no-results">
                <p>You haven't shortlisted any cleaners yet. <a href="searchCleaners.php">Find cleaners to shortlist</a>.</p>
            </div>
        <?php else: ?>
            <div class="shortlisted-cleaners">
                <?php foreach ($shortlistedCleaners as $cleaner): ?>
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
                            <form action="../../Controller/actions/removeFromShortlist.php" method="POST">
                                <input type="hidden" name="cleaner_id" value="<?php echo $cleaner['id']; ?>">
                                <button type="submit" class="remove-btn">Remove from Shortlist</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>