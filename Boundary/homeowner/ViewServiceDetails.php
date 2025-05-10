<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Black-Yellow/Controller/homeowner/ViewServiceDetailsController.php');

$serviceId = $_GET['service_id'] ?? null;

if ($serviceId === null) {
    echo "<div class='alert error'><strong>Error:</strong> No service ID provided.</div>";
    exit;
}

// Increment view count
ViewServiceDetailsController::incrementViewCount($serviceId);

// Get service details
$service = ViewServiceDetailsController::getServiceById($serviceId);

if ($service === null) {
    echo "<div class='alert error'><strong>Error:</strong> Service with ID $serviceId not found.</div>";
    exit;
}

$isLoggedIn = isset($_SESSION['userid']) && !empty($_SESSION['userid']);
$isHomeowner = $isLoggedIn && ($_SESSION['role'] === 'Homeowner' || $_SESSION['role'] === 'homeowner');

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_service'])) {
    if ($isLoggedIn && $isHomeowner) {
        $homeownerId = $_SESSION['userid'];
        $cleanerId = $service->getCleanerId();
        $bookingDate = $_POST['booking_date'] . ' ' . $_POST['booking_time'];
        
        // Process the booking
        $success = ViewServiceDetailsController::bookService($homeownerId, $cleanerId, $serviceId, $bookingDate);
        
        $statusMessage = $success ? 
            "<div class='alert success'><strong>Success!</strong> Your booking request has been submitted.</div>" : 
            "<div class='alert error'><strong>Error:</strong> There was a problem processing your booking request. Please try again.</div>";
    } else {
        $statusMessage = "<div class='alert warning'><strong>Notice:</strong> Please log in as a homeowner to book services.</div>";
    }
}

// Handle shortlisting the cleaner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    if ($isLoggedIn && $isHomeowner) {
        $homeownerId = $_SESSION['userid'];
        $result = ViewServiceDetailsController::toggleShortlist($service->getCleanerId(), $homeownerId);
        $statusMessage = $result ? 
            "<div class='alert success'><strong>Success:</strong> Shortlist updated successfully.</div>" : 
            "<div class='alert error'><strong>Error:</strong> Error updating shortlist.</div>";
    } else {
        $statusMessage = "<div class='alert warning'><strong>Notice:</strong> Please log in as a homeowner to shortlist cleaners.</div>";
    }
}

// Check if cleaner is shortlisted
$isShortlisted = false;
if ($isLoggedIn && $isHomeowner) {
    $homeownerId = $_SESSION['userid'];
    $isShortlisted = ViewServiceDetailsController::isCleanerShortlisted($service->getCleanerId(), $homeownerId);
}

// Format date range for availability
$availabilityText = $service->getAvailability();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service->getTitle()); ?> - Service Details</title>
    <link rel="stylesheet" href="/Black-Yellow/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Include the header (topbar and navbar) -->
    <?php include '../../assets/includes/header.php'; ?>
    
    <div class="service-details-container">
        <div class="service-card">
            <!-- Back button and alerts -->
            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            
            <?php if (isset($statusMessage)) echo $statusMessage; ?>
            
            <div class="service-header">
                <div class="service-title-section">
                    <h1><?php echo htmlspecialchars($service->getTitle()); ?></h1>
                    <div>
                        <span class="service-category">
                            <i class="fas fa-tag"></i> 
                            <?php echo htmlspecialchars($service->getCategory() ?? 'General'); ?>
                        </span>
                        <div class="service-meta">
                            <span><i class="fas fa-eye"></i> <?php echo $service->getViewCount(); ?> views</span>
                            <span><i class="fas fa-bookmark"></i> <?php echo $service->getShortlistCount(); ?> shortlists</span>
                        </div>
                    </div>
                </div>
                <div class="service-price">
                    <?php echo htmlspecialchars($service->getFormattedPrice()); ?>
                </div>
            </div>
            
            <div class="service-content">
                <div class="service-image-container">
                    <?php if ($service->getImagePath()): ?>
                        <img src="/Black-Yellow/<?php echo htmlspecialchars($service->getImagePath()); ?>" alt="<?php echo htmlspecialchars($service->getTitle()); ?>" class="service-image">
                    <?php else: ?>
                        <div class="service-image">
                            <i class="fas fa-image"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="service-details">
                    <h2>Description</h2>
                    <div class="service-description">
                        <?php echo htmlspecialchars($service->getDescription() ?? 'No description available.'); ?>
                    </div>
                    
                    <div class="service-availability">
                        <h3><i class="fas fa-calendar-alt"></i> Availability</h3>
                        <p><?php echo htmlspecialchars($availabilityText); ?></p>
                    </div>
                    
                    <div class="cleaner-info">
                        <img src="/Black-Yellow/assets/cleaners/default.jpg" 
                             alt="<?php echo htmlspecialchars($service->getCleanerName()); ?>" 
                             class="cleaner-avatar">
                        <div>
                            <h3>Provided by</h3>
                            <p><?php echo htmlspecialchars($service->getCleanerName()); ?></p>
                            <a href="ViewCleanerProfile.php?cleaner_id=<?php echo $service->getCleanerId(); ?>" class="profile-link">
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
            
            <div class="booking-section">
                <?php if ($isLoggedIn && $isHomeowner): ?>
                    <form method="POST" class="booking-form">
                        <h2>Book This Service</h2>
                        
                        <div class="form-group">
                            <label for="booking_date">Preferred Date</label>
                            <input type="date" id="booking_date" name="booking_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="booking_time">Preferred Time</label>
                            <input type="time" id="booking_time" name="booking_time" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="booking_notes">Additional Notes</label>
                            <textarea id="booking_notes" name="booking_notes" placeholder="Any special requests or information for the cleaner..."></textarea>
                        </div>
                        
                        <button type="submit" name="book_service" class="book-btn">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                    </form>
                    
                    <form method="POST">
                        <input type="hidden" name="toggle_shortlist" value="1">
                        <button type="submit" class="shortlist-btn <?php echo $isShortlisted ? 'remove' : ''; ?>">
                            <i class="fas fa-bookmark"></i> 
                            <?php echo $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert warning">
                        <strong>Notice:</strong> Please <a href="/Black-Yellow/public/login.php" style="color: #FFD700;">log in</a> as a homeowner to book this service or add to shortlist.
                    </div>
                <?php endif; ?>
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