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
require_once(__DIR__ . '/../../Controller/homeowner/ServiceDetailsController.php');
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

// Handle booking request
$bookingMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_service'])) {
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];
    $notes = $_POST['booking_notes'] ?? '';
    
    $bookingDateTime = $date . ' ' . $time . ':00';
    
    $result = $controller->bookService($homeownerId, $service->getCleanerId(), $serviceId, $bookingDateTime, $notes);
    
    if ($result) {
        $bookingMessage = '<div class="alert success">Booking request submitted successfully!</div>';
    } else {
        $bookingMessage = '<div class="alert error">Failed to submit booking request. Please try again.</div>';
    }
}

// Handle shortlist toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    $controller->toggleShortlist($service->getCleanerId(), $homeownerId);
    // Redirect to refresh page and avoid form resubmission
    header("Location: ViewServiceDetails.php?id=$serviceId");
    exit();
}

// Clear the buffer to remove any debug output
ob_clean();
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

<!-- Include the header -->
<?php include '../../assets/includes/header.php'; ?>

<div class="service-details-container">
    <div class="service-card">
        <a href="ViewCleanerProfile.php?id=<?php echo $service->getCleanerId(); ?>" class="back-button">← Back to Cleaner Profile</a>
        
        <?php if ($bookingMessage) echo $bookingMessage; ?>
        
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
        
        <div class="booking-section">
            <form method="POST" class="booking-form">
                <h2>Book This Service</h2>
                
                <div class="booking-fields">
                    <div class="form-group">
                        <label for="booking_date">Preferred Date</label>
                        <input type="date" id="booking_date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="booking_time">Preferred Time</label>
                        <input type="time" id="booking_time" name="booking_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="booking_notes">Additional Notes</label>
                        <textarea id="booking_notes" name="booking_notes" placeholder="Any special requests or information..."></textarea>
                    </div>
                    
                    <button type="submit" name="book_service" class="book-btn">Book Now</button>
                </div>
            </form>
            
            <form method="POST">
                <input type="hidden" name="toggle_shortlist" value="1">
                <?php if ($isShortlisted): ?>
                    <button type="submit" class="shortlist-btn remove">Remove Cleaner from Shortlist</button>
                <?php else: ?>
                    <button type="submit" class="shortlist-btn">Add Cleaner to Shortlist</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('booking_date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });
</script>

</body>
</html>