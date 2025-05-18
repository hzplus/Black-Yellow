<?php
// Improved CleanerProfile.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/CleanerProfileController.php';

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new CleanerProfileController();

// Get cleaner ID from URL
$cleanerId = $_GET['id'] ?? 0;

// Validate cleaner ID
if (!$cleanerId) {
    echo "Invalid cleaner ID.";
    exit();
}

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Get cleaner data
$cleaner = $controller->getCleanerById($cleanerId);

// Check if cleaner exists
if (!$cleaner) {
    echo "Cleaner not found.";
    exit();
}

// Check if cleaner is shortlisted by the current homeowner
$isShortlisted = $controller->isShortlisted($cleanerId, $homeownerId);

// Handle shortlist toggle if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_shortlist'])) {
    $controller->toggleShortlist($cleanerId, $homeownerId);
    
    // Refresh page to update shortlist status
    header("Location: CleanerProfile.php?id=$cleanerId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cleaner['username']); ?> - Cleaner Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .profile-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            margin: 20px 0;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .profile-header-section {
            display: flex;
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--bg-darker);
        }
        
        .profile-picture-container {
            flex-shrink: 0;
            margin-right: 30px;
        }
        
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid var(--primary);
            background-color: var(--bg-light);
        }
        
        .profile-header-content {
            flex: 1;
        }
        
        .profile-header {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 2rem;
        }
        
        .profile-info {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .shortlist-form {
            margin-top: 20px;
        }
        
        .shortlist-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background-color: var(--primary);
            color: var(--bg-darker);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .shortlist-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        .shortlist-btn.remove {
            background-color: #333;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .shortlist-btn.remove:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }
        
        .shortlist-btn:before {
            content: '★ ';
            margin-right: 8px;
        }
        
        .shortlist-btn.remove:before {
            content: '☆ ';
        }
        
        .services-section, 
        .contact-section {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .section-title {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .service-box {
            background-color: var(--bg-darker);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .service-box:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .service-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 15px;
            text-align: center;
        }
        
        .service-title {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .service-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .service-category {
            color: var(--text-muted);
            font-size: 0.9rem;
            background-color: rgba(255, 215, 0, 0.1);
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: auto;
        }
        
        .no-services {
            text-align: center;
            padding: 30px;
            color: var(--text-muted);
            font-style: italic;
        }
        
        .contact-section p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .contact-section p:before {
            content: '✉️';
            margin-right: 10px;
            color: var(--primary);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-header-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .profile-picture-container {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="profile-container">
    <div class="profile-card">
        <a href="BrowseCleaners.php" class="back-button">← Back to Cleaners</a>
        
        <div class="profile-header-section">
            <div class="profile-picture-container">
                <img src="../../assets/images/cleaners/<?php echo htmlspecialchars($cleaner['profile_image']); ?>" 
                     alt="<?php echo htmlspecialchars($cleaner['username']); ?>" 
                     class="profile-picture">
            </div>
            
            <div class="profile-header-content">
                <h2 class="profile-header"><?php echo htmlspecialchars($cleaner['username']); ?></h2>
                
                <div class="profile-info">
                    <?php echo htmlspecialchars($cleaner['bio']); ?>
                </div>
                
                <div id="shortlist-container">
                    <form method="POST" id="shortlist-form" class="shortlist-form">
                        <input type="hidden" name="toggle_shortlist" value="1">
                        
                        <button type="submit" id="shortlist-btn" class="shortlist-btn <?php echo $isShortlisted ? 'remove' : ''; ?>">
                            <?php echo $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="services-section">
            <h3 class="section-title">Services Offered</h3>
            
            <?php if(empty($cleaner['services'])): ?>
                <p class="no-services">No services currently offered by this cleaner.</p>
            <?php else: ?>
                <div class="services-grid">
                    <?php foreach($cleaner['services'] as $service): ?>
                        <a href="ServiceDetails.php?id=<?php echo $service['serviceid']; ?>" class="service-box">
                            <div class="service-icon">
                                <!-- Use appropriate icon based on service category -->
                                <?php 
                                $icon = 'broom'; // Default icon
                                $category = strtolower($service['category']);
                                
                                if (strpos($category, 'floor') !== false) $icon = 'mop';
                                else if (strpos($category, 'window') !== false) $icon = 'window';
                                else if (strpos($category, 'toilet') !== false) $icon = 'toilet';
                                else if (strpos($category, 'laundry') !== false) $icon = 'tshirt';
                                ?>
                                
                                <i class="fas fa-<?php echo $icon; ?>"></i>
                            </div>
                            <div class="service-title"><?php echo htmlspecialchars($service['title']); ?></div>
                            <div class="service-price"><?php echo $controller->formatPrice($service['price']); ?></div>
                            <div class="service-category"><?php echo htmlspecialchars($service['category']); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="contact-section">
            <h3 class="section-title">Contact Information</h3>
            <p>Email: <?php echo htmlspecialchars($cleaner['email']); ?></p>
        </div>
    </div>
</div>

</body>
</html>