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
$viewType = $_GET['view'] ?? 'history'; // 'history' or 'cleaners'
$selectedCleanerId = $_GET['cleaner_id'] ?? 0;

// Get service history
$serviceHistory = $controller->getServiceHistory(
    $homeownerId, 
    $startDate, 
    $endDate, 
    $cleanerName, 
    $category
);

// Get all previous cleaners
$previousCleaners = $controller->getPreviousCleaners($homeownerId);

// Get specific cleaner's services if a cleaner is selected
$cleanerServices = [];
if ($selectedCleanerId > 0) {
    $cleanerServices = $controller->getServicesFromCleaner($homeownerId, $selectedCleanerId);
    // Get cleaner name for display
    foreach ($previousCleaners as $cleaner) {
        if ($cleaner['userid'] == $selectedCleanerId) {
            $selectedCleanerName = $cleaner['username'];
            break;
        }
    }
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
        
        .view-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .view-tab {
            padding: 12px 25px;
            font-weight: 600;
            cursor: pointer;
            color: var(--text-muted);
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .view-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .view-tab:hover {
            color: var(--primary);
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
        
        /* Cleaner Cards */
        .cleaners-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .cleaner-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 20px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        
        .cleaner-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        .cleaner-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background-color: var(--bg-darker);
            border: 2px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            overflow: hidden;
        }
        
        .cleaner-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .cleaner-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 8px;
        }
        
        .cleaner-count {
            color: var(--text-muted);
            margin-bottom: 15px;
        }
        
        .cleaner-actions {
            margin-top: auto;
        }
        
        /* Cleaner Services List */
        .cleaner-services-list {
            margin-top: 20px;
        }
        
        .service-item {
            background-color: var(--bg-light);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .service-item:hover {
            border-color: var(--primary);
            background-color: rgba(255, 215, 0, 0.05);
        }
        
        .service-info {
            flex: 1;
        }
        
        .service-title {
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .service-details {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .service-price {
            font-weight: bold;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .no-results {
            grid-column: span 3;
            text-align: center;
            padding: 40px;
            background-color: var(--bg-light);
            border-radius: 12px;
            color: var(--text-muted);
            font-style: italic;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .history-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .cleaners-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .no-results {
                grid-column: span 2;
            }
        }
        
        @media (max-width: 768px) {
            .history-container,
            .cleaners-container {
                grid-template-columns: 1fr;
            }
            
            .no-results {
                grid-column: span 1;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .history-actions {
                flex-direction: column;
            }
            
            .view-tabs {
                flex-direction: column;
                border-bottom: none;
            }
            
            .view-tab {
                border-bottom: none;
                border-left: 3px solid transparent;
            }
            
            .view-tab.active {
                border-bottom: none;
                border-left-color: var(--primary);
                background-color: rgba(255, 215, 0, 0.05);
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
    
    <!-- View Tabs -->
    <div class="view-tabs">
        <a href="?view=history" class="view-tab <?= $viewType === 'history' ? 'active' : '' ?>">
            <i class="fas fa-history"></i> Service History
        </a>
        <a href="?view=cleaners" class="view-tab <?= $viewType === 'cleaners' ? 'active' : '' ?>">
            <i class="fas fa-user-friends"></i> Previous Cleaners
        </a>
    </div>
    
    <?php if ($viewType === 'history'): ?>
        <!-- Filter Form for History View -->
        <form method="GET" action="" class="filter-row">
            <input type="hidden" name="view" value="history">
            
            <div>
                <label for="start_date">From:</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate); ?>">
            </div>
            
            <div>
                <label for="end_date">To:</label>
                <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate); ?>">
            </div>
            
            <div>
                <label for="cleaner_name">Cleaner:</label>
                <input type="text" id="cleaner_name" name="cleaner_name" placeholder="Cleaner name..." value="<?= htmlspecialchars($cleanerName); ?>">
            </div>
            
            <div>
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
            
            <button type="submit">Search</button>
            <a href="ServiceHistory.php?view=history" class="clear-btn">Clear Filters</a>
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
                            <?= htmlspecialchars($service['formatted_date']); ?>
                        </div>
                        
                        <div class="history-details">
                            <h3><?= htmlspecialchars($service['service_title']); ?></h3>
                            <p>
                                <strong>Cleaner:</strong> 
                                <?= htmlspecialchars($service['cleaner_name']); ?>
                            </p>
                            <p>
                                <strong>Category:</strong> 
                                <?= htmlspecialchars($service['category']); ?>
                            </p>
                        </div>
                        
                        <div class="history-price">
                            <?= htmlspecialchars($service['formatted_price']); ?>
                        </div>
                        
                        <div class="history-actions">
                            <a href="CleanerProfile.php?id=<?= $service['cleanerid']; ?>" class="btn btn-outline">
                                View Cleaner Profile
                            </a>
                            <a href="ServiceDetails.php?id=<?= $service['serviceid']; ?>" class="btn">
                                View Service Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    <?php elseif ($viewType === 'cleaners'): ?>
        <?php if ($selectedCleanerId > 0): ?>
            <!-- Cleaner's Services View -->
            <div class="back-button" onclick="window.history.back()">← Back to Cleaners List</div>
            
            <h2 class="sub-title">Services by <?= htmlspecialchars($selectedCleanerName ?? ''); ?></h2>
            
            <div class="cleaner-services-list">
                <?php if (empty($cleanerServices)): ?>
                    <div class="no-results">
                        <p>No services found for this cleaner.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($cleanerServices as $service): ?>
                        <div class="service-item">
                            <div class="service-info">
                                <div class="service-title"><?= htmlspecialchars($service['title']); ?></div>
                                <div class="service-details">
                                    <?= htmlspecialchars($service['formatted_date']); ?> • 
                                    <?= htmlspecialchars($service['category']); ?>
                                </div>
                            </div>
                            <div class="service-price">
                                <?= htmlspecialchars($service['formatted_price']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="CleanerProfile.php?id=<?= $selectedCleanerId; ?>" class="btn">
                            View Cleaner's Profile
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <!-- Previous Cleaners View -->
            <form method="GET" action="" class="filter-row">
                <input type="hidden" name="view" value="cleaners">
                
                <div>
                    <label for="cleaner_search">Search Cleaners:</label>
                    <input type="text" id="cleaner_search" name="cleaner_name" placeholder="Cleaner name..." value="<?= htmlspecialchars($cleanerName); ?>">
                </div>
                
                <button type="submit">Search</button>
                <a href="ServiceHistory.php?view=cleaners" class="clear-btn">Clear Search</a>
            </form>
            
            <div class="cleaners-container">
                <?php if(empty($previousCleaners)): ?>
                    <div class="no-results">
                        <p>You haven't worked with any cleaners yet.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $filteredCleaners = $previousCleaners;
                    if (!empty($cleanerName)) {
                        $filteredCleaners = array_filter($previousCleaners, function($cleaner) use ($cleanerName) {
                            return stripos($cleaner['username'], $cleanerName) !== false;
                        });
                    }
                    
                    if(empty($filteredCleaners)): 
                    ?>
                        <div class="no-results">
                            <p>No cleaners found matching your search.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($filteredCleaners as $cleaner): ?>
                            <div class="cleaner-card">
                                <div class="cleaner-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="cleaner-name"><?= htmlspecialchars($cleaner['username']); ?></div>
                                <div class="cleaner-count">
                                    <?= $cleaner['service_count']; ?> service<?= $cleaner['service_count'] > 1 ? 's' : ''; ?> completed
                                </div>
                                <div class="cleaner-actions">
                                    <a href="?view=cleaners&cleaner_id=<?= $cleaner['userid']; ?>" class="btn btn-outline">
                                        View Service History
                                    </a>
                                    <a href="CleanerProfile.php?id=<?= $cleaner['userid']; ?>" class="btn" style="margin-top: 10px;">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    // Add any necessary JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // You could add functionality like date range validation, etc.
    });
</script>

</body>
</html>