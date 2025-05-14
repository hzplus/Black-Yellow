<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Include controller
require_once(__DIR__ . '/../../Controller/homeowner/ServiceHistoryController.php');
$controller = new ServiceHistoryController();

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Handle filtering
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$cleanerName = $_GET['cleaner_name'] ?? '';
$category = $_GET['category'] ?? '';

// Get service history
$serviceHistory = $controller->getServiceHistory($homeownerId, $startDate, $endDate, $cleanerName, $category);

// Get all service categories for filter dropdown
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service History - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">Service History</h1>
    
    <!-- Filter Form -->
    <form method="GET" action="" class="filter-row">
        <div class="filter-group">
            <label for="start_date">From:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
        </div>
        
        <div class="filter-group">
            <label for="end_date">To:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
        </div>
        
        <div class="filter-group">
            <label for="cleaner_name">Cleaner:</label>
            <input type="text" id="cleaner_name" name="cleaner_name" placeholder="Cleaner name..." value="<?php echo htmlspecialchars($cleanerName); ?>">
        </div>
        
        <div class="filter-group">
            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($category === $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit">Filter</button>
        <a href="ViewServiceHistory.php" class="clear-btn">Clear Filters</a>
    </form>
    
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
                        <?php echo htmlspecialchars($service->getFormattedDate()); ?>
                    </div>
                    
                    <div class="history-details">
                        <h3><?php echo htmlspecialchars($service->getServiceTitle()); ?></h3>
                        <p class="cleaner-name">
                            Cleaner: <?php echo htmlspecialchars($service->getCleanerName()); ?>
                        </p>
                        <p class="service-category">
                            Category: <?php echo htmlspecialchars($service->getCategory()); ?>
                        </p>
                        <?php if($service->getNotes()): ?>
                            <p class="service-notes">Notes: <?php echo htmlspecialchars($service->getNotes()); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="history-price">
                        <?php echo htmlspecialchars($service->getFormattedPrice()); ?>
                    </div>
                    
                    <div class="history-actions">
                        <a href="ViewCleanerProfile.php?id=<?php echo $service->getCleanerId(); ?>" class="view-profile-btn">
                            View Cleaner
                        </a>
                        <a href="ViewServiceDetails.php?id=<?php echo $service->getServiceId(); ?>" class="view-service-btn">
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