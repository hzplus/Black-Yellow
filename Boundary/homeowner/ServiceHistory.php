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
    <style>
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .section-title {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
            text-align: center;
        }
        
        .sub-title {
            font-size: 1.5rem;
            color: var(--primary);
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert.success {
            background-color: rgba(76, 175, 80, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        .alert.error {
            background-color: rgba(244, 67, 54, 0.1);
            border: 1px solid var(--error);
            color: var(--error);
        }
        
        .filter-row {
            background-color: var(--bg-light);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        .filter-row input,
        .filter-row select {
            flex: 1;
            min-width: 150px;
            padding: 12px 15px;
            border-radius: 5px;
            background-color: var(--bg-darker);
            border: 1px solid var(--border-color);
            color: var(--text-light);
        }
        
        .filter-row button {
            padding: 12px 25px;
            background-color: var(--primary);
            color: var(--bg-darker);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-row button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        .filter-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            margin-bottom: 0;
        }
        
        .filter-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }
        
        .clear-btn {
            padding: 12px 20px;
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .clear-btn:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        /* Booking Cards for Pending Bookings */
        .pending-bookings {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .booking-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--primary);
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        .booking-card::before {
            content: 'PENDING';
            position: absolute;
            top: 10px;
            right: -30px;
            background-color: var(--primary);
            color: var(--bg-darker);
            font-size: 0.7rem;
            font-weight: bold;
            padding: 5px 30px;
            transform: rotate(45deg);
        }
        
        .booking-date {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--primary);
            font-weight: bold;
        }
        
        .booking-details h3 {
            color: var(--text-light);
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .cleaner-name, 
        .service-category, 
        .service-notes {
            margin-bottom: 8px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .booking-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--primary);
            margin: 15px 0;
        }
        
        .booking-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .cancel-btn {
            flex: 1;
            padding: 10px 0;
            background-color: transparent;
            color: var(--error);
            border: 1px solid var(--error);
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cancel-btn:hover {
            background-color: rgba(244, 67, 54, 0.1);
            transform: translateY(-2px);
        }
        
        .view-profile-btn,
        .view-service-btn {
            flex: 1;
            padding: 10px 0;
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .view-profile-btn {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .view-profile-btn:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .view-service-btn {
            background-color: var(--primary);
            color: var(--bg-darker);
            border: none;
        }
        
        .view-service-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* History Cards */
        .history-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .history-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .history-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        .history-date {
            font-weight: bold;
            color: var(--text-light);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .history-details h3 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .history-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--primary);
            margin: 15px 0;
        }
        
        .history-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .no-results {
            grid-column: span 2;
            text-align: center;
            padding: 40px;
            background-color: var(--bg-light);
            border-radius: 12px;
            color: var(--text-muted);
            font-style: italic;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .pending-bookings,
            .history-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .pending-bookings,
            .history-container {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .booking-actions,
            .history-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Service History</h1>
    
    <?php if (isset($cancelSuccess)): ?>
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            <strong>Success:</strong> Your booking has been canceled.
        </div>
    <?php endif; ?>
    
    <?php if (isset($cancelError)): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
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