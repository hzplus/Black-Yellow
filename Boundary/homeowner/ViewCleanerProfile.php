<?php
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
    $controller->toggleShortlist($cleanerId, $homeownerId);
    // Refresh page to update shortlist status
    header("Location: ViewCleanerProfile.php?id=$cleanerId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cleaner->getName()); ?> - Cleaner Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .shortlist-btn {
            transition: all 0.3s ease;
        }
        .shortlist-btn:hover {
            transform: scale(1.05);
        }
        .service-box {
            transition: all 0.3s ease;
            display: block;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .service-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .service-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #4a90e2;
        }
        .service-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .service-price {
            font-size: 18px;
            color: #2ecc71;
            margin-bottom: 5px;
        }
        .service-category {
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>

<!-- Include the header -->
<?php include '../../assets/includes/header.php'; ?>

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

<script>
    // Enhanced shortlist button
    document.addEventListener('DOMContentLoaded', function() {
        const shortlistForm = document.getElementById('shortlist-form');
        const shortlistBtn = document.getElementById('shortlist-btn');
        
        shortlistForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Visual feedback
            shortlistBtn.disabled = true;
            shortlistBtn.textContent = shortlistBtn.classList.contains('remove') ? 'Removing...' : 'Adding...';
            
            // Submit form
            fetch(shortlistForm.action, {
                method: 'POST',
                body: new FormData(shortlistForm)
            })
            .then(response => response.json().catch(() => ({})))
            .then(data => {
                // Toggle button appearance
                shortlistBtn.classList.toggle('remove');
                shortlistBtn.textContent = shortlistBtn.classList.contains('remove') ? 'Remove from Shortlist' : 'Add to Shortlist';
                shortlistBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                shortlistBtn.disabled = false;
                // Just submit the form normally if there's an error
                shortlistForm.submit();
            });
        });
    });
</script>

</body>
</html>