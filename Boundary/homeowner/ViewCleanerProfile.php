<?php
// Start output buffering at the top of the file to capture unwanted debug output
ob_start();

session_start();

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Include controller
require_once(__DIR__ . '/../../Controller/homeowner/CleanerProfileController.php');
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

// Get services offered by this cleaner
$services = $controller->getCleanerServices($cleanerId);

// Check if cleaner is shortlisted by the current homeowner
$isShortlisted = $controller->isShortlisted($cleanerId, $homeownerId);

// Handle shortlist toggle if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    // Fix the parameter order for toggle function
    $controller->toggleShortlist($cleanerId, $homeownerId);
    
    // Refresh page to update shortlist status
    header("Location: ViewCleanerProfile.php?id=$cleanerId");
    exit();
}

// Clear the output buffer to remove any debug output
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cleaner->getName()); ?> - Cleaner Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .profile-card {
            background-color: #1a1a1a; /* Dark background */
            color: #e0e0e0; /* Light text */
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #FFD700; /* Gold color */
            font-weight: bold;
        }
        
        .profile-header-section {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            gap: 30px;
        }
        
        .profile-picture-container {
            flex: 0 0 150px; /* Fixed width for profile picture */
        }
        
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid #FFD700;
        }
        
        .profile-header-content {
            flex: 1;
        }
        
        .profile-header {
            margin-top: 0;
            margin-bottom: 15px;
            color: #FFD700; /* Gold color */
        }
        
        .profile-info {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .shortlist-btn {
            padding: 10px 20px;
            background-color: #FFD700;
            color: #333;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .shortlist-btn.remove {
            background-color: #f44336;
            color: white;
        }
        
        .shortlist-btn:hover {
            transform: scale(1.05);
        }
        
        .section-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: #FFD700; /* Gold color */
            border-bottom: 2px solid #FFD700;
            padding-bottom: 8px;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .service-box {
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
            color: inherit;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 15px;
            background-color: #252525; /* Slightly lighter than background */
        }
        
        .service-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-color: #FFD700;
        }
        
        .service-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #FFD700; /* Gold color */
        }
        
        .service-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #ffffff;
        }
        
        .service-price {
            font-size: 18px;
            color: #4cd964; /* Green color */
            margin-bottom: 5px;
        }
        
        .service-category {
            color: #999;
            font-size: 14px;
        }
        
        .contact-section {
            border-top: 1px solid #444;
            padding-top: 20px;
        }
        
        @media (max-width: 767px) {
            .profile-header-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .profile-picture-container {
                margin-bottom: 20px;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="profile-container">
    <div class="profile-card">
        <a href="ViewCleanerListings.php" class="back-button">← Back to Listings</a>
        
        <div class="profile-header-section">
            <div class="profile-picture-container">
                <img src="../../assets/images/cleaners/<?php echo htmlspecialchars($cleaner->getProfileImage()) ?: 'default.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($cleaner->getName()); ?>" 
                     class="profile-picture">
            </div>
            
            <div class="profile-header-content">
                <h2 class="profile-header"><?php echo htmlspecialchars($cleaner->getName()); ?></h2>
                
                <div class="profile-info">
                    <?php echo htmlspecialchars($cleaner->getBio()); ?>
                </div>
                
                <div id="shortlist-container">
                    <!-- Fixed form that uses direct POST submission, not AJAX -->
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
            
            <?php if(empty($services)): ?>
                <p class="no-services">No services currently offered by this cleaner.</p>
            <?php else: ?>
                <div class="services-grid">
                    <?php foreach($services as $service): ?>
                        <a href="ViewServiceDetails.php?id=<?php echo $service->getId(); ?>" class="service-box">
                            <div class="service-icon">
                                <!-- Use appropriate icon based on service category -->
                                <?php 
                                $icon = 'broom'; // Default icon
                                $category = strtolower($service->getCategory());
                                
                                if (strpos($category, 'floor') !== false) $icon = 'mop';
                                else if (strpos($category, 'window') !== false) $icon = 'window';
                                else if (strpos($category, 'toilet') !== false) $icon = 'toilet';
                                else if (strpos($category, 'laundry') !== false) $icon = 'tshirt';
                                ?>
                                
                                <i class="fas fa-<?php echo $icon; ?>"></i>
                            </div>
                            <div class="service-title"><?php echo htmlspecialchars($service->getTitle()); ?></div>
                            <div class="service-price"><?php echo htmlspecialchars($service->getFormattedPrice()); ?></div>
                            <div class="service-category"><?php echo htmlspecialchars($service->getCategory()); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="contact-section">
            <h3 class="section-title">Contact Information</h3>
            <p>Email: <?php echo htmlspecialchars($cleaner->getEmail()); ?></p>
            <?php if($cleaner->getPhone()): ?>
                <p>Phone: <?php echo htmlspecialchars($cleaner->getPhone()); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>