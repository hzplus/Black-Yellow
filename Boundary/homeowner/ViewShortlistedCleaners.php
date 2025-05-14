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

// Function to get profile image with fallback options
function getCleanerImagePath($cleanerId, $name) {
    // Try to use local placeholder images that might exist in your project
    $localImages = [
        '../../assets/images/cleaners/default1.jpg',
        '../../assets/images/cleaners/default2.jpg',
        '../../assets/images/cleaners/default3.jpg',
        '../../assets/images/cleaners/default4.jpg',
        '../../assets/images/cleaners/default.jpg',
    ];
    
    // Select an image based on cleaner ID
    $imageIndex = $cleanerId % count($localImages);
    $imagePath = $localImages[$imageIndex];
    
    // Return the selected local image path
    return $imagePath;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Cleaners - Cleaning Service</title>
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
            background-color: #1a1a1a; /* Dark background */
            color: #e0e0e0; /* Light text */
            border-radius: var(--border-radius-md);
            border: 1px solid #444;
            transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        }
        
        .cleaner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-color: #FFD700;
        }
        
        .card-header {
            padding: var(--spacing-sm) var(--spacing-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .card-body {
            padding: var(--spacing-md);
            display: flex;
            gap: var(--spacing-md);
        }
        
        .cleaner-name {
            color: #FFD700; /* Gold color */
            margin: 0;
        }
        
        .view-profile-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #FFD700;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        
        .view-profile-btn:hover {
            background-color: #FFC800;
            transform: translateY(-2px);
        }
        
        .cleaner-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: var(--border-radius-sm);
            border: 2px solid #FFD700;
        }
        
        .service-tag {
            display: inline-block;
            padding: 4px 8px;
            margin: 2px;
            background-color: #252525; /* Slightly lighter than background */
            color: #e0e0e0;
            border-radius: 4px;
            font-size: 0.85em;
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
            <a href="ViewShortlistedCleaners.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Grid in 2x2 Layout -->
    <div class="cleaner-grid">
        <?php if (empty($cleaners)): ?>
            <div style="text-align: center; grid-column: span 2; padding: 40px 0;">
                <p>You haven't shortlisted any cleaners yet.</p>
                <a href="ViewCleanerListings.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #FFD700; color: black; text-decoration: none; border-radius: 5px; font-weight: bold;">Find Cleaners</a>
            </div>
        <?php else: ?>
            <?php foreach ($cleaners as $cleaner): ?>
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
                            
                            <!-- Removed "Remove from Shortlist" button and replaced with view profile button -->
                            <a href="ViewCleanerProfile.php?id=<?= $cleaner->getId(); ?>" class="view-profile-btn" style="width: 100%; margin-top: 15px; display: block;">
                                View Profile
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