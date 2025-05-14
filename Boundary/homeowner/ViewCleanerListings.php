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
require_once(__DIR__ . '/../../Controller/homeowner/ShortlistController.php');

$controller = new CleanerListingsController();
$shortlistController = new ShortlistController();

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

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
    <title>Available Cleaners - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Additional styles for 2x2 grid layout */
        .cleaner-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-lg);
            margin-top: var(--spacing-xl);
        }
        
        .cleaner-card {
            position: relative;
            background-color: var(--bg-light);
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border-color);
            transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        }
        
        .cleaner-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .card-header {
            padding: var(--spacing-sm) var(--spacing-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            background-color: rgba(255, 215, 0, 0.05);
        }
        
        .card-body {
            padding: var(--spacing-md);
            display: flex;
            gap: var(--spacing-md);
        }
        
        .cleaner-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: var(--border-radius-sm);
            border: 2px solid var(--primary);
        }
        
        .view-profile-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #FFD700;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        
        .view-profile-btn:hover {
            background-color: #FFC800;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .cleaner-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>
    
<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Available Cleaners</h1>
    
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
        
        <select name="sort">
            <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Sort by Name</option>
            <option value="price" <?= $sortBy === 'price' ? 'selected' : '' ?>>Sort by Price</option>
        </select>
        
        <button type="submit">Search</button>
        <?php if(!empty($search) || !empty($category) || $sortBy !== 'name'): ?>
            <a href="ViewCleanerListings.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Grid in 2x2 Layout -->
    <div class="cleaner-grid">
        <?php if(empty($cleaners)): ?>
            <div style="text-align: center; grid-column: span 2; padding: 40px 0;">
                <p>No cleaners found matching your criteria.</p>
            </div>
        <?php else: ?>
            <?php foreach($cleaners as $cleaner): ?>
                <div class="cleaner-card">
                    <div class="card-header">
                        <h3 class="cleaner-name"><?= htmlspecialchars($cleaner->getName()) ?></h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <img src="../../assets/images/cleaners/default.jpg" 
                                 alt="<?= htmlspecialchars($cleaner->getName()) ?>" 
                                 class="cleaner-image">
                        </div>
                        <div style="flex: 1;">
                            <div class="services-list">
                                <h4>Services Offered:</h4>
                                <div class="cleaner-services">
                                    <?php 
                                    $services = $cleaner->getServices();
                                    $displayCount = min(count($services), 3);
                                    for($i = 0; $i < $displayCount; $i++): 
                                    ?>
                                        <span class="service-tag"><?= htmlspecialchars($services[$i]->getTitle()); ?></span>
                                    <?php endfor; ?>
                                    
                                    <?php if(count($services) > 3): ?>
                                        <span class="service-tag">+<?= count($services) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- "View Profile" link styled as a button instead of shortlist button -->
                            <a href="ViewCleanerProfile.php?id=<?= $cleaner->getId(); ?>" class="view-profile-btn" style="width: 100%; margin-top: 15px;">
                                View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>