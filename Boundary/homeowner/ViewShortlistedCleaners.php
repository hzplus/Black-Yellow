<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Include controller
require_once(__DIR__ . '/../../Controller/homeowner/ShortlistController.php');
$controller = new ShortlistController();

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Handle direct removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $cleanerId = $_POST['remove_id'];
    $controller->removeFromShortlist($cleanerId, $homeownerId);
    // Redirect to refresh the page
    header("Location: ViewShortlistedCleaners.php");
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Get shortlisted cleaners
if (!empty($search) || !empty($category)) {
    $cleaners = $controller->searchShortlistedCleaners($homeownerId, $search, $category);
} else {
    $cleaners = $controller->getShortlistedCleaners($homeownerId);
}

// Get all service categories for filter dropdown
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Cleaners - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header -->
<?php include '../../assets/includes/header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Shortlisted Cleaners</h1>
    
    <!-- Search and Filter Form -->
    <form method="GET" action="" class="filter-row">
        <input type="text" name="search" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
        
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category === $cat) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Search</button>
        <?php if(!empty($search) || !empty($category)): ?>
            <a href="ViewShortlistedCleaners.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Listings -->
    <div class="cleaner-grid">
        <?php if(empty($cleaners)): ?>
            <div class="no-results">
                <p>You haven't shortlisted any cleaners yet.</p>
                <a href="ViewCleanerListings.php" class="action-btn">Browse Cleaners</a>
            </div>
        <?php else: ?>
            <?php foreach($cleaners as $cleaner): ?>
                <div class="cleaner-card">
                    <div class="card-header">
                        <a href="ViewCleanerProfile.php?id=<?php echo $cleaner->getId(); ?>">View Profile</a>
                    </div>
                    <div class="card-body">
                        <div class="card-left">
                            <img src="../../assets/images/cleaners/<?php echo htmlspecialchars($cleaner->getProfileImage()) ?: 'default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($cleaner->getName()); ?>" 
                                 class="cleaner-image">
                        </div>
                        <div class="card-right">
                            <div class="card-info">
                                <p class="cleaner-name"><?php echo htmlspecialchars($cleaner->getName()); ?></p>
                                
                                <?php if($cleaner->getAvgPrice() > 0): ?>
                                    <p class="cleaner-price">
                                        Average Price: $<?php echo htmlspecialchars(number_format($cleaner->getAvgPrice(), 2)); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="services-list">
                                <h4>Services Offered:</h4>
                                <div class="cleaner-services">
                                    <?php 
                                    $services = $cleaner->getServices();
                                    $displayCount = min(count($services), 3);
                                    for($i = 0; $i < $displayCount; $i++): 
                                    ?>
                                        <span class="service-tag"><?php echo htmlspecialchars($services[$i]->getTitle()); ?></span>
                                    <?php endfor; ?>
                                    
                                    <?php if(count($services) > 3): ?>
                                        <span class="service-tag">+<?php echo count($services) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <form method="POST" class="shortlist-form">
                                <input type="hidden" name="remove_id" value="<?php echo $cleaner->getId(); ?>">
                                <button type="submit" class="shortlist-btn remove">
                                    Remove from Shortlist
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>