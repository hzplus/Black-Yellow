<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

// Check if cleaner ID is provided
if (!isset($_GET['id'])) {
    header("Location: searchCleaners.php");
    exit();
}

$cleanerId = $_GET['id'];

// Include controller
require_once '../../Controller/HomeownerController.php';
$homeownerController = new HomeownerController();

// Get cleaner information
$cleaner = $homeownerController->getCleanerProfile($cleanerId);

// Check if cleaner exists
if (!$cleaner) {
    header("Location: searchCleaners.php");
    exit();
}

// Check if cleaner is shortlisted
$isShortlisted = $homeownerController->isCleanerShortlisted($_SESSION['userid'], $cleanerId);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cleaner Profile - Black&Yellow</title>
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
        <a href="serviceHistory.php">Service History</a>
        <a href="myAccount.php">My Account</a>
    </div>
    
    <!-- Cleaner Profile -->
    <div class="content-container">
        <div class="profile-header">
            <a href="javascript:history.back()" class="back-btn">Back</a>
            <h2>Cleaner Profile</h2>
        </div>
        
        <div class="cleaner-profile">
            <div class="profile-left">
                <div class="profile-picture">
                    <img src="../../assets/images/profile-placeholder.png" alt="Profile Picture">
                </div>
                
                <div class="shortlist-action">
                    <?php if (!$isShortlisted): ?>
                        <form action="../../Controller/actions/addToShortlist.php" method="POST">
                            <input type="hidden" name="cleaner_id" value="<?php echo $cleanerId; ?>">
                            <button type="submit" class="shortlist-btn">Add to Shortlist</button>
                        </form>
                    <?php else: ?>
                        <form action="../../Controller/actions/removeFromShortlist.php" method="POST">
                            <input type="hidden" name="cleaner_id" value="<?php echo $cleanerId; ?>">
                            <button type="submit" class="remove-btn">Remove from Shortlist</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="profile-right">
                <div class="profile-info-section">
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
                    
                    <div class="bio">
                        <p><?php echo $cleaner['bio']; ?></p>
                    </div>
                </div>
                
                <div class="services-section">
                    <h4>Services Offered</h4>
                    <div class="services-grid">
                        <?php foreach ($cleaner['services'] as $service): ?>
                            <div class="service-box">
                                <h5><?php echo $service['name']; ?></h5>
                                <p class="service-price">$<?php echo $service['price']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="availability-section">
                    <h4>Availability</h4>
                    <p><?php echo $cleaner['availability']; ?></p>
                </div>
                
                <div class="reviews-section">
                    <h4>Reviews</h4>
                    <?php if (empty($cleaner['reviews'])): ?>
                        <p>No reviews yet.</p>
                    <?php else: ?>
                        <?php foreach ($cleaner['reviews'] as $review): ?>
                            <div class="review">
                                <div class="review-header">
                                    <span class="reviewer-name"><?php echo $review['reviewer_name']; ?></span>
                                    <span class="review-date"><?php echo date('M d, Y', strtotime($review['date'])); ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['rating']) {
                                            echo '<span class="star filled">★</span>';
                                        } else {
                                            echo '<span class="star">☆</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <p class="review-text"><?php echo $review['comment']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>