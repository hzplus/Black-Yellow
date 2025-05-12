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
require_once(__DIR__ . '/../../Controller/homeowner/CleanerListingsController.php');
require_once(__DIR__ . '/../../Controller/homeowner/ShortlistController.php'); // Add this line

$controller = new CleanerListingsController();
$shortlistController = new ShortlistController(); // Add this line for direct shortlisting

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Handle shortlist action directly in this page - Add this block
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cleaner_id']) && isset($_POST['action'])) {
    $cleanerId = $_POST['cleaner_id'];
    $action = $_POST['action'];
    
    if ($action === 'add') {
        $shortlistController->addToShortlist($cleanerId, $homeownerId);
    } else if ($action === 'remove') {
        $shortlistController->removeFromShortlist($cleanerId, $homeownerId);
    }
    
    // Redirect to the same page to avoid form resubmission
    $queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header("Location: ViewCleanerListings.php" . $queryString);
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sortBy = $_GET['sort'] ?? 'name';

// Get cleaners
if (!empty($search) || !empty($category)) {
    $cleaners = $controller->searchCleaners($search, $category, $sortBy);
} else {
    $cleaners = $controller->getAllCleaners($sortBy);
}

// Get categories for dropdown
$categories = $controller->getAllCategories();

// Get shortlisted cleaners for the current homeowner
$shortlistedIds = $controller->getShortlistedCleanerIds($homeownerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaner Listings - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header -->
<?php include '../../assets/includes/header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Available Cleaners</h1>
    
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
        
        <select name="sort">
            <option value="name" <?php echo ($sortBy === 'name') ? 'selected' : ''; ?>>Sort by Name</option>
            <option value="price" <?php echo ($sortBy === 'price') ? 'selected' : ''; ?>>Sort by Price</option>
        </select>
        
        <button type="submit">Search</button>
        <?php if(!empty($search) || !empty($category) || $sortBy !== 'name'): ?>
            <a href="ViewCleanerListings.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Listings -->
    <div class="cleaner-grid">
        <?php if(empty($cleaners)): ?>
            <div class="no-results">
                <p>No cleaners found matching your criteria.</p>
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
                            
                            <!-- Changed form action to submit to this page instead of the handler -->
                            <form method="POST" action="" class="shortlist-form">
                                <input type="hidden" name="cleaner_id" value="<?php echo $cleaner->getId(); ?>">
                                
                                <?php if(in_array($cleaner->getId(), $shortlistedIds)): ?>
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="shortlist-btn remove">
                                        Remove from Shortlist
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="shortlist-btn">
                                        Add to Shortlist
                                    </button>
                                <?php endif; ?>
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