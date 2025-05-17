<?php
<<<<<<< Updated upstream
require_once(__DIR__ . '/../../controller/HomeownerController.php');
session_start();

// Get the homeowner ID from the session
$homeownerId = $_SESSION['userid'] ?? null;

// Redirect if not logged in
if (!$homeownerId) {
=======
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Boundary/homeowner/ShortlistedCleaners.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/ShortlistedCleanersController.php';

// Redirect if not logged in as homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
>>>>>>> Stashed changes
    header("Location: ../login.php");
    exit();
}

<<<<<<< Updated upstream
$controller = new HomeownerController();

// Handle POST requests for toggling shortlist status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cleaner_id'])) {
    $controller->toggleShortlist($_POST['cleaner_id'], $homeownerId);
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get the shortlisted cleaners for this homeowner
$cleaners = $controller->getShortlistedCleaners($homeownerId);

// Handle search and filtering if implemented
$searchTerm = $_GET['search'] ?? '';
$filterType = $_GET['filter'] ?? 'name';

if ($searchTerm) {
    $filteredCleaners = [];
    foreach ($cleaners as $cleaner) {
        // Filter by name
        if ($filterType === 'name' && stripos($cleaner->getName(), $searchTerm) !== false) {
            $filteredCleaners[] = $cleaner;
        } 
        // Filter by service title
        elseif ($filterType === 'service') {
            $services = $cleaner->getServices();
            foreach ($services as $service) {
                if (stripos($service->getTitle(), $searchTerm) !== false) {
                    $filteredCleaners[] = $cleaner;
                    break;
                }
            }
        }
    }
    $cleaners = $filteredCleaners;
}
=======
// Create controller instance
$controller = new ShortlistedCleanersController();

// Get homeowner ID from session
$homeownerId = $_SESSION['userid'];

// Handle removal from shortlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $cleanerId = $_POST['remove_id'];
    $controller->removeFromShortlist($homeownerId, $cleanerId);
    // Redirect to prevent form resubmission
    header("Location: ShortlistedCleaners.php");
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Get shortlisted cleaners based on search parameters
if (!empty($search) || !empty($category)) {
    $cleaners = $controller->searchShortlisted($homeownerId, $search, $category);
} else {
    $cleaners = $controller->getShortlistedCleaners($homeownerId);
}

// Get categories for dropdown
$categories = $controller->getAllCategories();
>>>>>>> Stashed changes
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< Updated upstream
    <title>Shortlisted Cleaners</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../assets/includes/header.php'; ?>
    
    <!-- Back Button (Similar to CleanerListings.php) -->
    <a href="HomeownerDashboard.php" class="back-button">← Back</a>
=======
    <title>Shortlisted Cleaners - Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>
    
<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
>>>>>>> Stashed changes
    
    <h1 class="section-title">Shortlisted Cleaners</h1>
    
    <!-- Search and Filter Form -->
    <form method="GET" class="filter-row">
<<<<<<< Updated upstream
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchTerm) ?>">
        <select name="filter">
            <option value="name" <?= $filterType === 'name' ? 'selected' : '' ?>>By Name</option>
            <option value="service" <?= $filterType === 'service' ? 'selected' : '' ?>>By Service</option>
        </select>
        <button type="submit">Search</button>
    </form>
    
    <!-- Cleaner Grid Section in 2x2 Grid (similar to CleanerListings.php) -->
=======
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Search</button>
        <?php if(!empty($search) || !empty($category)): ?>
            <a href="ShortlistedCleaners.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Grid -->
>>>>>>> Stashed changes
    <div class="cleaner-grid">
        <?php if (empty($cleaners)): ?>
            <div style="text-align: center; grid-column: span 2; padding: 40px 0;">
                <p>You haven't shortlisted any cleaners yet.</p>
<<<<<<< Updated upstream
                <a href="CleanerListings.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #FFD700; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;">Find Cleaners</a>
            </div>
        <?php else: ?>
            <?php foreach ($cleaners as $cleaner): ?>
                <?php
                    $firstService = !empty($cleaner->getServices()) ? $cleaner->getServices()[0] : null;
                    $price = $firstService ? "$" . number_format($firstService->getPrice(), 2) : "Price not available";
                    $availability = $firstService ? $firstService->getAvailability() : "Availability not set";
                ?>
                <div class="cleaner-card">
                    <div class="card-header">
                        <a href="ViewCleanerProfile.php?id=<?= $cleaner->getId(); ?>" class="profile-link">View Profile</a>
                    </div>
                    <div class="card-body">
                        <div class="card-left">
                            <img src="../../assets/cleaners/ben.jpg" alt="<?= htmlspecialchars($cleaner->getName()) ?>" class="cleaner-image">
                        </div>
                        <div class="card-right">
                            <div class="card-info">
                                <p class="cleaner-name">Name: <?= htmlspecialchars($cleaner->getName()) ?></p>
                                <p class="cleaner-price">Price: <?= htmlspecialchars($price) ?></p>
                                <p class="cleaner-availability">Availability: <?= htmlspecialchars($availability) ?></p>
                            </div>
                            <div class="services-list">
                                <h4>Services Offered:</h4>
                                <?php if (!empty($cleaner->getServices())): ?>
                                    <ul>
                                        <?php 
                                        $services = $cleaner->getServices();
                                        $displayCount = min(count($services), 2);
                                        for ($i = 0; $i < $displayCount; $i++): 
                                        ?>
                                            <li>
                                                <span class="service-title"><?= htmlspecialchars($services[$i]->getTitle()) ?></span>
                                            </li>
                                        <?php endfor; ?>
                                        <?php if (count($services) > 2): ?>
                                            <li>+ <?= count($services) - 2 ?> more services</li>
                                        <?php endif; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No services listed</p>
                                <?php endif; ?>
                            </div>
                            <form method="POST" class="shortlist-form">
                                <input type="hidden" name="cleaner_id" value="<?= $cleaner->getId() ?>">
                                <button type="submit" class="shortlist-btn">Remove from Shortlist</button>
                            </form>
=======
                <a href="BrowseCleaners.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #FFD700; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;">Find Cleaners</a>
            </div>
        <?php else: ?>
            <?php foreach ($cleaners as $cleaner): ?>
                <div class="cleaner-card">
                    <div class="card-header">
                        <h3 class="cleaner-name"><?= htmlspecialchars($cleaner['username']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <img src="../../assets/images/cleaners/default.jpg"
                                 alt="<?= htmlspecialchars($cleaner['username']) ?>" 
                                 class="cleaner-image">
                        </div>
                        <div style="flex: 1;">
                            <div class="services-list">
                                <h4>Services Offered:</h4>
                                <div class="cleaner-services">
                                    <?php 
                                    $services = $cleaner['services'];
                                    $displayCount = min(count($services), 3);
                                    for($i = 0; $i < $displayCount; $i++): 
                                    ?>
                                        <span class="service-tag"><?= htmlspecialchars($services[$i]['title']); ?></span>
                                    <?php endfor; ?>
                                    
                                    <?php if(count($services) > 3): ?>
                                        <span class="service-tag">+<?= count($services) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <!-- View Profile button -->
                                <a href="CleanerProfile.php?id=<?= $cleaner['userid']; ?>" class="view-profile-btn">
                                    View Profile
                                </a>
                                
                                <!-- Remove from shortlist form -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="remove_id" value="<?= $cleaner['userid'] ?>">
                                    <button type="submit" class="remove-btn" onclick="return confirm('Remove this cleaner from your shortlist?')">
                                        Remove
                                    </button>
                                </form>
                            </div>
>>>>>>> Stashed changes
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<<<<<<< Updated upstream
=======
</div>

>>>>>>> Stashed changes
</body>
</html>