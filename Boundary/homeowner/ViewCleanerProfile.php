<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Controller/homeowner/ViewCleanerProfileController.php');

// Get the cleaner ID from the URL
$cleanerId = $_GET['id'] ?? $_GET['cleaner_id'] ?? null;

if ($cleanerId === null) {
    echo "<div class='alert error'><strong>Error:</strong> No cleaner ID provided.</div>";
    exit;
}

// Get the cleaner details
$cleaner = ViewCleanerProfileController::getCleanerById($cleanerId);

if ($cleaner === null) {
    echo "<div class='alert error'><strong>Error:</strong> Cleaner with ID $cleanerId not found in the database.</div>";
    exit;
}

// Check if the user is logged in and is a homeowner
$isLoggedIn = isset($_SESSION['userid']) && !empty($_SESSION['userid']);
$isHomeowner = $isLoggedIn && ($_SESSION['role'] === 'Homeowner' || $_SESSION['role'] === 'homeowner');

if (!$isLoggedIn || !$isHomeowner) {
    $statusMessage = "<div class='alert warning'><strong>Notice:</strong> You are viewing this page as a guest. Please log in as a homeowner to shortlist cleaners.</div>";
    $canShortlist = false;
} else {
    $canShortlist = true;
    $homeownerId = $_SESSION['userid'];
    $isShortlisted = ViewCleanerProfileController::isCleanerShortlisted($cleanerId, $homeownerId);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['remove_id'])) {
            $result = ViewCleanerProfileController::toggleShortlist($_POST['remove_id'], $homeownerId);
            $statusMessage = $result ? 
                "<div class='alert success'><strong>Success:</strong> Cleaner removed from shortlist.</div>" : 
                "<div class='alert error'><strong>Error:</strong> Error removing cleaner from shortlist.</div>";
        } else if (isset($_POST['cleaner_id'])) {
            $result = ViewCleanerProfileController::toggleShortlist($_POST['cleaner_id'], $homeownerId);
            $statusMessage = $result ? 
                "<div class='alert success'><strong>Success:</strong> Cleaner added to shortlist.</div>" : 
                "<div class='alert error'><strong>Error:</strong> Error adding cleaner to shortlist.</div>";
        }
        
        // Re-check shortlist status after action
        $isShortlisted = ViewCleanerProfileController::isCleanerShortlisted($cleanerId, $homeownerId);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaner Profile - <?php echo htmlspecialchars($cleaner->getName()); ?></title>
    <link rel="stylesheet" href="/Black-Yellow/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/header.php'; ?>
    
    <!-- Back Button -->
    <div class="back-button-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Profile Content -->
    <div class="profile-container">
        <div class="profile-card">
            <!-- Alert Messages (if any) -->
            <?php if (isset($statusMessage)) echo $statusMessage; ?>

            <div class="profile-header-section">
                <div class="profile-picture-container">
                    <img src="/Black-Yellow/assets/cleaners/<?php echo htmlspecialchars($cleaner->getProfilePicture() ?: 'default.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($cleaner->getName()); ?>'s Profile Picture" 
                         class="profile-picture"
                         onerror="this.src='/Black-Yellow/assets/cleaners/default.jpg'">
                </div>
                <div class="profile-header-content">
                    <h2 class="profile-header"><?php echo htmlspecialchars($cleaner->getName()); ?></h2>
                    <div class="profile-info">
                        <?php echo htmlspecialchars($cleaner->getBio() ?? 'No bio available.'); ?>
                    </div>
                </div>
            </div>

            <div class="services-section">
                <h3 class="section-title">
                    <i class="fas fa-broom"></i> Services Offered
                </h3>
                
                <div class="services-grid">
                    <?php
                    $services = $cleaner->getServices();
                    if (is_array($services) && !empty($services)):
                        foreach ($services as $service): 
                            // Choose an icon based on the service category
                            $icon = 'fa-broom'; // default
                            if ($service->getCategory()) {
                                switch (strtolower($service->getCategory())) {
                                    case 'floor': $icon = 'fa-mop'; break;
                                    case 'laundry': $icon = 'fa-tshirt'; break;
                                    case 'toilet': $icon = 'fa-toilet'; break;
                                    case 'window': $icon = 'fa-window-maximize'; break;
                                    case 'all-in-one': $icon = 'fa-house-user'; break;
                                }
                            }
                    ?>
                            <a href="ViewServiceDetails.php?service_id=<?php echo $service->getId(); ?>" class="service-box">
                                <div class="service-icon">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <div class="service-title"><?php echo htmlspecialchars($service->getTitle()); ?></div>
                                <div class="service-price"><?php echo htmlspecialchars($service->getFormattedPrice()); ?></div>
                                <?php if ($service->getCategory()): ?>
                                    <div class="service-category"><?php echo htmlspecialchars($service->getCategory()); ?></div>
                                <?php endif; ?>
                            </a>
                        <?php endforeach;
                    else: ?>
                        <div class="no-services">
                            <p>No services available from this cleaner.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="shortlist-button-container">
                <?php if ($canShortlist): ?>
                    <form action="" method="POST">
                        <?php if ($isShortlisted): ?>
                            <input type="hidden" name="remove_id" value="<?php echo $cleaner->getId(); ?>">
                            <button type="submit" class="shortlist-button remove">
                                <i class="fas fa-bookmark"></i> Remove from Shortlist
                            </button>
                        <?php else: ?>
                            <input type="hidden" name="cleaner_id" value="<?php echo $cleaner->getId(); ?>">
                            <button type="submit" class="shortlist-button">
                                <i class="fas fa-bookmark"></i> Add to Shortlist
                            </button>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <button class="shortlist-button" disabled>
                        <i class="fas fa-user"></i> Login as Homeowner to Shortlist
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Animation for service boxes
        document.addEventListener('DOMContentLoaded', function() {
            const serviceBoxes = document.querySelectorAll('.service-box');
            
            serviceBoxes.forEach((box, index) => {
                box.style.opacity = '0';
                box.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    box.style.transition = 'all 0.5s ease';
                    box.style.opacity = '1';
                    box.style.transform = 'translateY(0)';
                }, 100 * (index + 1));
            });
        });
    </script>
</body>
</html>