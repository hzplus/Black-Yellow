<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Boundary/homeowner/ServiceDetails.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/ServiceDetailsController.php';

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new ServiceDetailsController();

// Get service ID from URL
$serviceId = $_GET['id'] ?? 0;

// Validate service ID
if (!$serviceId) {
    echo "Invalid service ID.";
    exit();
}

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Display booking success message if applicable
if (isset($_GET['booked']) && $_GET['booked'] == 1) {
    $bookingSuccess = true;
} else {
    $bookingSuccess = false;
}

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
$cleaner = $controller->getCleanerById($service['cleanerid']);

// Check if cleaner is shortlisted
$isShortlisted = $controller->isCleanerShortlisted($service['cleanerid'], $homeownerId);

// Handle shortlist toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    $controller->toggleShortlist($service['cleanerid'], $homeownerId);
    // Redirect to refresh page and avoid form resubmission
    header("Location: ServiceDetails.php?id=$serviceId");
    exit();
}

// Handle booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_date'])) {
    $bookingDate = $_POST['booking_date'];
    $notes = $_POST['notes'] ?? '';
    
    $bookingId = $controller->bookService($serviceId, $service['cleanerid'], $homeownerId, $bookingDate, $notes);
    
    if ($bookingId) {
        // Redirect to success page
        header("Location: ServiceDetails.php?id=$serviceId&booked=1");
        exit();
    } else {
        $bookingError = "Failed to book service. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($service['title']); ?> - Service Details</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="service-details-container">
    <div class="service-card">
        <a href="CleanerProfile.php?id=<?= $service['cleanerid']; ?>" class="back-button">← Back to Cleaner Profile</a>
        
        <?php if ($bookingSuccess): ?>
            <div class="alert success">
                <strong>Success:</strong> Your booking has been confirmed!
            </div>
        <?php endif; ?>
        
        <?php if (isset($bookingError)): ?>
            <div class="alert error">
                <strong>Error:</strong> <?= $bookingError; ?>
            </div>
        <?php endif; ?>

        <div class="service-header">
            <div class="service-title-section">
                <h1><?= htmlspecialchars($service['title']); ?></h1>
                <div class="service-meta">
                    <span class="service-category"><?= htmlspecialchars($service['category']); ?></span>
                    <span><?= $service['view_count']; ?> views</span>
                </div>
            </div>
            <div class="service-price"><?= $service['formatted_price']; ?></div>
        </div>
        
        <div class="service-content">
            <div class="service-image-container">
                <?php if ($service['image_path']): ?>
                    <img src="../../<?= htmlspecialchars($service['image_path']); ?>" alt="<?= htmlspecialchars($service['title']); ?>" class="service-image">
                <?php else: ?>
                    <div class="service-image"><i class="fas fa-image"></i></div>
                <?php endif; ?>
            </div>
            
            <div class="service-details">
                <h2>Description</h2>
                <div class="service-description">
                    <?= htmlspecialchars($service['description']); ?>
                </div>
                
                <div class="availability-section">
                    <h3>Availability</h3>
                    <p><?= htmlspecialchars($service['availability']); ?></p>
                </div>
                
                <div class="cleaner-info">
                    <img src="../../assets/images/cleaners/<?= htmlspecialchars($cleaner['profile_image']); ?>" 
                         alt="<?= htmlspecialchars($cleaner['username']); ?>" 
                         class="cleaner-avatar">
                    <div>
                        <h3>Provided by</h3>
                        <p><?= htmlspecialchars($cleaner['username']); ?></p>
                        <a href="CleanerProfile.php?id=<?= $cleaner['userid']; ?>" class="profile-link">
                            View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stats-section">
            <div class="stat-item">
                <div class="stat-number"><?= $service['view_count']; ?></div>
                <div class="stat-label">Views</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $service['shortlist_count'] ?? 0; ?></div>
                <div class="stat-label">Shortlists</div>
            </div>
        </div>
        
        <div class="booking-section">
            <form method="POST">
                <input type="hidden" name="toggle_shortlist" value="1">
                <button type="submit" class="shortlist-btn <?= $isShortlisted ? 'remove' : ''; ?>">
                    <i class="fas fa-bookmark"></i> 
                    <?= $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
                </button>
            </form>
            
            <h3>Book This Service</h3>
            <form method="POST" class="booking-form">
                <div class="form-group">
                    <label for="booking_date">Choose Date:</label>
                    <input type="date" id="booking_date" name="booking_date" required>
                </div>
                
                <div class="form-group">
                    <label for="notes">Special Instructions (Optional):</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
                
                <button type="submit" class="booking-btn">Book Now</button>
            </form>
        </div>
    </div>
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