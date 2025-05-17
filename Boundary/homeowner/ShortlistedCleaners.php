<?php
// Boundary/homeowner/ShortlistedCleaners.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/ShortlistedCleanersController.php';

// Redirect if not logged in as homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new ShortlistedCleanersController();

// Get homeowner ID from session
$homeownerId = $_SESSION['userid'];

// Handle removal from shortlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $cleanerId = $_POST['remove_id'];
    $controller->removeFromShortlist($homeownerId, $cleanerId);
    // Redirect to prevent form resubmission
    header("Location: ShortlistedCleaners.php");
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Get shortlisted cleaners based on search parameters
if (!empty($search) || !empty($category)) {
    $cleaners = $controller->searchShortlisted($homeownerId, $search, $category);
} else {
    $cleaners = $controller->getShortlistedCleaners($homeownerId);
}

// Get categories for dropdown
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shortlisted Cleaners - Black&Yellow Cleaning</title>
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
        
        /* 2x2 Grid Layout for Cleaners */
        .cleaner-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 20px;
        }
        
        .cleaner-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .cleaner-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            padding: 15px 20px;
            background-color: var(--bg-darker);
            border-bottom: 1px solid var(--border-color);
        }
        
        .cleaner-name {
            color: var(--primary);
            margin: 0;
            font-size: 1.4rem;
        }
        
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }
        
        .cleaner-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--primary);
        }
        
        .services-list {
            margin-top: 15px;
        }
        
        .cleaner-services {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }
        
        .service-tag {
            background-color: rgba(255, 215, 0, 0.1);
            color: var(--primary);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            border: 1px solid var(--primary);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .view-profile-btn {
            flex: 1;
            padding: 10px 15px;
            background-color: var(--primary);
            color: var(--bg-darker);
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        
        .view-profile-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .remove-btn {
            flex: 1;
            padding: 10px 15px;
            background-color: transparent;
            color: var(--primary);
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid var(--primary);
            cursor: pointer;
        }
        
        .remove-btn:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 0;
            grid-column: span 2;
        }
        
        .empty-state p {
            margin-bottom: 20px;
            color: var(--text-muted);
            font-size: 1.1rem;
        }
        
        .find-cleaners-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary);
            color: var(--bg-darker);
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .find-cleaners-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .cleaner-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .cleaner-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-row input,
            .filter-row select,
            .filter-row button,
            .clear-btn {
                width: 100%;
            }
            
            .action-buttons {
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
    
    <h1 class="section-title">Shortlisted Cleaners</h1>
    
    <!-- Search and Filter Form -->
    <form method="GET" class="filter-row">
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Search</button>
        <?php if(!empty($search) || !empty($category)): ?>
            <a href="ShortlistedCleaners.php" class="clear-btn">Clear Filters</a>
        <?php endif; ?>
    </form>
    
    <!-- Cleaner Grid -->
    <div class="cleaner-grid">
        <?php if (empty($cleaners)): ?>
            <div class="empty-state">
                <p>You haven't shortlisted any cleaners yet.</p>
                <a href="BrowseCleaners.php" class="find-cleaners-btn">Find Cleaners</a>
            </div>
        <?php else: ?>
            <?php foreach ($cleaners as $cleaner): ?>
                <div class="cleaner-card">
                    <div class="card-header">
                        <h3 class="cleaner-name"><?= htmlspecialchars($cleaner['username']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <img src="../../assets/images/cleaners/default.jpg"
                                 alt="<?= htmlspecialchars($cleaner['username']) ?>" 
                                 class="cleaner-image">
                        </div>
                        <div style="flex: 1;">
                            <div class="services-list">
                                <h4>Services Offered:</h4>
                                <div class="cleaner-services">
                                    <?php 
                                    $services = $cleaner['services'];
                                    $displayCount = min(count($services), 3);
                                    for($i = 0; $i < $displayCount; $i++): 
                                    ?>
                                        <span class="service-tag"><?= htmlspecialchars($services[$i]['title']); ?></span>
                                    <?php endfor; ?>
                                    
                                    <?php if(count($services) > 3): ?>
                                        <span class="service-tag">+<?= count($services) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <!-- View Profile button -->
                                <a href="CleanerProfile.php?id=<?= $cleaner['userid']; ?>" class="view-profile-btn">
                                    View Profile
                                </a>
                                
                                <!-- Remove from shortlist form -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="remove_id" value="<?= $cleaner['userid'] ?>">
                                    <button type="submit" class="remove-btn" onclick="return confirm('Remove this cleaner from your shortlist?')">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>