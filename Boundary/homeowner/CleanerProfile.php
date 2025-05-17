<?php
// Boundary/homeowner/CleanerProfile.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/CleanerProfileController.php';

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new CleanerProfileController();

// Get cleaner ID from URL
$cleanerId = $_GET['id'] ?? 0;

// Validate cleaner ID
if (!$cleanerId) {
    echo "Invalid cleaner ID.";
    exit();
}

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Get cleaner data
$cleaner = $controller->getCleanerById($cleanerId);

// Check if cleaner exists
if (!$cleaner) {
    echo "Cleaner not found.";
    exit();
}

// Check if cleaner is shortlisted by the current homeowner
$isShortlisted = $controller->isShortlisted($cleanerId, $homeownerId);

// Handle shortlist toggle if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    $controller->toggleShortlist($cleanerId, $homeownerId);
    
    // Refresh page to update shortlist status
    header("Location: CleanerProfile.php?id=$cleanerId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cleaner['username']); ?> - Cleaner Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="profile-container">
    <div class="profile-card">
        <a href="BrowseCleaners.php" class="back-button">← Back to Cleaners</a>
        
        <div class="profile-header-section">
            <div class="profile-picture-container">
                <img src="../../assets/images/cleaners/<?php echo htmlspecialchars($cleaner['profile_image']); ?>" 
                     alt="<?php echo htmlspecialchars($cleaner['username']); ?>" 
                     class="profile-picture">
            </div>
            
            <div class="profile-header-content">
                <h2 class="profile-header"><?php echo htmlspecialchars($cleaner['username']); ?></h2>
                
                <div class="profile-info">
                    <?php echo htmlspecialchars($cleaner['bio']); ?>
                </div>
                
                <div id="shortlist-container">
                    <form method="POST" id="shortlist-form" class="shortlist-form">
                        <input type="hidden" name="toggle_shortlist" value="1">
                        
                        <button type="submit" id="shortlist-btn" class="shortlist-btn <?php echo $isShortlisted ? 'remove' : ''; ?>">
                            <?php echo $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="services-section">
            <h3 class="section-title">Services Offered</h3>
            
            <?php if(empty($cleaner['services'])): ?>
                <p class="no-services">No services currently offered by this cleaner.</p>
            <?php else: ?>
                <div class="services-grid">
                    <?php foreach($cleaner['services'] as $service): ?>
                        <a href="ServiceDetails.php?id=<?php echo $service['serviceid']; ?>" class="service-box">
                            <div class="service-icon">
                                <!-- Use appropriate icon based on service category -->
                                <?php 
                                $icon = 'broom'; // Default icon
                                $category = strtolower($service['category']);
                                
                                if (strpos($category, 'floor') !== false) $icon = 'mop';
                                else if (strpos($category, 'window') !== false) $icon = 'window';
                                else if (strpos($category, 'toilet') !== false) $icon = 'toilet';
                                else if (strpos($category, 'laundry') !== false) $icon = 'tshirt';
                                ?>
                                
                                <i class="fas fa-<?php echo $icon; ?>"></i>
                            </div>
                            <div class="service-title"><?php echo htmlspecialchars($service['title']); ?></div>
                            <div class="service-price"><?php echo $controller->formatPrice($service['price']); ?></div>
                            <div class="service-category"><?php echo htmlspecialchars($service['category']); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="contact-section">
            <h3 class="section-title">Contact Information</h3>
            <p>Email: <?php echo htmlspecialchars($cleaner['email']); ?></p>
        </div>
    </div>
</div>

</body>
</html>