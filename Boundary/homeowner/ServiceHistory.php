<?php
// Boundary/homeowner/ServiceHistory.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/ServiceHistoryController.php';

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new ServiceHistoryController();

// Get homeowner ID from session
$homeownerId = $_SESSION['userid'];

// Handle filter parameters
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$cleanerName = $_GET['cleaner_name'] ?? '';
$category = $_GET['category'] ?? '';
$showPending = isset($_GET['show_pending']) && $_GET['show_pending'] == '1';

// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $bookingId = $_POST['booking_id'];
    $success = $controller->cancelBooking($bookingId, $homeownerId);
    
    if ($success) {
        $cancelSuccess = true;
    } else {
        $cancelError = "Failed to cancel booking. Please try again.";
    }
}

// Get service history
$serviceHistory = $controller->getServiceHistory(
    $homeownerId, 
    $startDate, 
    $endDate, 
    $cleanerName, 
    $category
);

// Get pending bookings if requested
if ($showPending) {
    $pendingBookings = $controller->getPendingBookings($homeownerId);
} else {
    $pendingBookings = [];
}

// Get all categories for filter dropdown
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service History - Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Service History</h1>
    
    <?php if (isset($cancelSuccess)): ?>
        <div class="alert success">
            <strong>Success:</strong> Your booking has been canceled.
        </div>
    <?php endif; ?>
    
    <?php if (isset($cancelError)): ?>
        <div class="alert error">
            <strong>Error:</strong> <?= $cancelError; ?>
        </div>
    <?php endif; ?>
    
    <!-- Filter Form -->
    <form method="GET" action="" class="filter-row">
        <div class="filter-group">
            <label for="start_date">From:</label>
            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate); ?>">
        </div>
        
        <div class="filter-group">
            <label for="end_date">To:</label>
            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate); ?>">
        </div>
        
        <div class="filter-group">
            <label for="cleaner_name">Cleaner:</label>
            <input type="text" id="cleaner_name" name="cleaner_name" placeholder="Cleaner name..." value="<?= htmlspecialchars($cleanerName); ?>">
        </div>
        
        <div class="filter-group">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat); ?>" <?= ($category === $cat) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="show_pending">
                <input type="checkbox" id="show_pending" name="show_pending" value="1" <?= $showPending ? 'checked' : ''; ?>>
                Show Pending Bookings
            </label>
        </div>
        
        <button type="submit">Filter</button>
        <a href="ServiceHistory.php" class="clear-btn">Clear Filters</a>
    </form>
    
    <!-- Pending Bookings Section (if requested) -->
    <?php if ($showPending && !empty($pendingBookings)): ?>
        <h2 class="sub-title">Pending Bookings</h2>
        <div class="pending-bookings">
            <?php foreach($pendingBookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-date">
                        <i class="fas fa-calendar"></i>
                        <?= htmlspecialchars($booking['formatted_date']); ?>
                    </div>
                    
                    <div class="booking-details">
                        <h3><?= htmlspecialchars($booking['title']); ?></h3>
                        <p class="cleaner-name">
                            Cleaner: <?= htmlspecialchars($booking['cleaner_name']); ?>
                        </p>
                        <p class="service-category">
                            Category: <?= htmlspecialchars($booking['category']); ?>
                        </p>
                        <?php if($booking['notes']): ?>
                            <p class="service-notes">Notes: <?= htmlspecialchars($booking['notes']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="booking-price">
                        <?= htmlspecialchars($booking['formatted_price']); ?>
                    </div>
                    
                    <div class="booking-actions">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            <input type="hidden" name="booking_id" value="<?= $booking['bookingid']; ?>">
                            <button type="submit" name="cancel_booking" class="cancel-btn">
                                Cancel Booking
                            </button>
                        </form>
                        
                        <a href="CleanerProfile.php?id=<?= $booking['cleanerid']; ?>" class="view-profile-btn">
                            View Cleaner
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Completed Service History -->
    <h2 class="sub-title">Completed Services</h2>
    
    <!-- History Listings -->
    <div class="history-container">
        <?php if(empty($serviceHistory)): ?>
            <div class="no-results">
                <p>No service history found matching your criteria.</p>
            </div>
        <?php else: ?>
            <?php foreach($serviceHistory as $service): ?>
                <div class="history-card">
                    <div class="history-date">
                        <?= htmlspecialchars($service['formatted_date']); ?>
                    </div>
                    
                    <div class="history-details">
                        <h3><?= htmlspecialchars($service['service_title']); ?></h3>
                        <p class="cleaner-name">
                            Cleaner: <?= htmlspecialchars($service['cleaner_name']); ?>
                        </p>
                        <p class="service-category">
                            Category: <?= htmlspecialchars($service['category']); ?>
                        </p>
                        <?php if($service['notes']): ?>
                            <p class="service-notes">Notes: <?= htmlspecialchars($service['notes']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="history-price">
                        <?= htmlspecialchars($service['formatted_price']); ?>
                    </div>
                    
                    <div class="history-actions">
                        <a href="CleanerProfile.php?id=<?= $service['cleanerid']; ?>" class="view-profile-btn">
                            View Cleaner
                        </a>
                        <a href="ServiceDetails.php?id=<?= $service['serviceid']; ?>" class="view-service-btn">
                            View Service
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html> 