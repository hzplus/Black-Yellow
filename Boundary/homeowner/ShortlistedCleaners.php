<?php
// Boundary/homeowner/ShortlistedCleaners.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/ShortlistedCleanersController.php';

// Redirect if not logged in as homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Cleaners - Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>
    
<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Shortlisted Cleaners</h1>
    
    <!-- Search and Filter Form -->
    <form method="GET" class="filter-row">
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
    <div class="cleaner-grid">
        <?php if (empty($cleaners)): ?>
            <div style="text-align: center; grid-column: span 2; padding: 40px 0;">
                <p>You haven't shortlisted any cleaners yet.</p>
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
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>