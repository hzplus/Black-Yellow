<?php
// Start output buffering at the top of the file to capture unwanted debug output
// ob_start();

session_start();
// echo $_SESSION['role']; // for debugging only

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Include controller
require_once(__DIR__ . '/../../Controller/homeowner/ServiceDetailsController.php');
$controller = new ServiceDetailsController();

// Get service ID from URL
$serviceId = $_GET['id'] ?? 0;

// Validate service ID
if (!$serviceId) {
    echo "Invalid service ID.";
    exit();
}

if (isset($_GET['booked']) && $_GET['booked'] == 1) {
    echo "<div class='alert success'><strong>Success:</strong> Your booking has been confirmed!</div>";
}

$isLoggedIn = isset($_SESSION['userid']) && !empty($_SESSION['userid']);
$isHomeowner = $isLoggedIn && ($_SESSION['role'] === 'Homeowner' || $_SESSION['role'] === 'homeowner');

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Get service data
$service = $controller->getServiceById($serviceId);

// Check if service exists
if (!$service) {
    echo "Service not found.";
    exit();
}

// Increment view count
$controller->incrementViewCount($serviceId);

// Get cleaner data
$cleaner = $controller->getCleanerById($service->getCleanerId());

// Check if cleaner is shortlisted
$isShortlisted = $controller->isCleanerShortlisted($service->getCleanerId(), $homeownerId);

// Handle shortlist toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    $controller->toggleShortlist($service->getCleanerId(), $homeownerId);
    // Redirect to refresh page and avoid form resubmission
    header("Location: ViewServiceDetails.php?id=$serviceId");
    exit();
}

// Clear the buffer to remove any debug output
// ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service->getTitle()); ?> - Service Details</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="service-details-container">
    <div class="service-card">
        <a href="ViewCleanerProfile.php?id=<?php echo $service->getCleanerId(); ?>" class="back-button">← Back to Cleaner Profile</a>
        

        <div class="service-header">
            <div class="service-title-section">
                <h1><?php echo htmlspecialchars($service->getTitle()); ?></h1>
                <div class="service-meta">
                    <span class="service-category"><?php echo htmlspecialchars($service->getCategory()); ?></span>
                    <span><?php echo $service->getViewCount(); ?> views</span>
                </div>
            </div>
            <div class="service-price">$<?php echo htmlspecialchars(number_format($service->getPrice(), 2)); ?></div>
        </div>
        
        <div class="service-content">
            <div class="service-image-container">
                <?php if ($service->getImagePath()): ?>
                    <img src="../../<?php echo htmlspecialchars($service->getImagePath()); ?>" alt="<?php echo htmlspecialchars($service->getTitle()); ?>" class="service-image">
                <?php else: ?>
                    <div class="service-image"><i class="fas fa-image"></i></div>
                <?php endif; ?>
            </div>
            
            <div class="service-details">
                <h2>Description</h2>
                <div class="service-description">
                    <?php echo htmlspecialchars($service->getDescription()); ?>
                </div>
                
                <div class="availability-section">
                    <h3>Availability</h3>
                    <p><?php echo htmlspecialchars($service->getAvailability()); ?></p>
                </div>
                
                <div class="cleaner-info">
                    <img src="../../assets/images/cleaners/<?php echo htmlspecialchars($cleaner->getProfileImage()) ?: 'default.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($cleaner->getName()); ?>" 
                         class="cleaner-avatar">
                    <div>
                        <h3>Provided by</h3>
                        <p><?php echo htmlspecialchars($cleaner->getName()); ?></p>
                        <a href="ViewCleanerProfile.php?id=<?php echo $cleaner->getId(); ?>" class="profile-link">
                            View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $service->getViewCount(); ?></div>
                    <div class="stat-label">Views</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $service->getShortlistCount(); ?></div>
                    <div class="stat-label">Shortlists</div>
                </div>
            </div>
            <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<div class="booking-section">
    <?php if ($isLoggedIn && $isHomeowner): ?>
        <form method="POST">
            <input type="hidden" name="toggle_shortlist" value="1">
            <button type="submit" class="shortlist-btn <?php echo $isShortlisted ? 'remove' : ''; ?>">
                <i class="fas fa-bookmark"></i> 
                <?php echo $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
            </button>
        </form>
    <?php else: ?>
        <div class="alert warning">
            <strong>Notice:</strong> Please <a href="/Black-Yellow/public/login.php" style="color: #FFD700;">log in</a> as a homeowner to add to shortlist.
        </div>
    <?php endif; ?>
</div>

<script>
        // Date restriction - only allow future dates
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('booking_date');
            if (dateInput) {
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                const formattedTomorrow = tomorrow.toISOString().split('T')[0];
                dateInput.setAttribute('min', formattedTomorrow);
            }
        });
    </script>

</body>
</html>