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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($service['title']); ?> - Service Details</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        :root {
            --primary: #FFD700;  /* Exact yellow from your other pages */
            --primary-dark: #d6b600;  /* Darker version for hover states */
            --bg-light: #222;  /* Matching your light backgrounds */
            --bg-darker: #111;  /* Matching your darker backgrounds */
            --text-light: #f5f5f5;  /* Light text color */
            --text-muted: #aaa;  /* Muted text color */
            --border-color: #333;  /* Border color from your other pages */
            --success: #4caf50;  /* Success color */
            --error: #f44336;  /* Error color */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-light);
            background-color: #121212;  /* Matching your body background */
            margin: 0;
            padding: 0;
        }
        
        .service-details-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .service-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            margin: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .service-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--bg-darker);
        }
        
        .service-title-section h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            color: var(--primary);
        }
        
        .service-meta {
            display: flex;
            gap: 15px;
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .service-category {
            background-color: rgba(255, 215, 0, 0.1);
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 500;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .service-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            background-color: rgba(255, 215, 0, 0.1);
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid var(--primary);
        }
        
        .service-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }
        
        .service-image-container {
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 16/9;
            border: 1px solid var(--border-color);
            background-color: var(--bg-darker);
        }
        
        .service-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .service-image:hover {
            transform: scale(1.05);
        }
        
        /* Placeholder for when no image is available */
        .service-image-container i.fas {
            font-size: 4rem;
            color: var(--text-muted);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
        }
        
        .service-image-container i.fas::after {
            content: "No image available";
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 10px;
        }
        
        .service-details h2 {
            margin-top: 0;
            color: var(--primary);
            font-size: 22px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }
        
        .service-description {
            margin-bottom: 25px;
            line-height: 1.7;
            color: var(--text-light);
        }
        
        .availability-section h3 {
            font-size: 18px;
            color: var(--primary);
            margin-bottom: 8px;
        }
        
        .cleaner-info {
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background-color: var(--bg-darker);
            border-radius: 10px;
            border-left: 3px solid var(--primary);
        }
        
        .cleaner-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        
        .cleaner-info h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: var(--text-muted);
        }
        
        .cleaner-info p {
            margin: 0 0 8px 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-light);
        }
        
        .profile-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .profile-link:hover {
            color: var(--primary-dark);
        }
        
        .stats-section {
            display: flex;
            padding: 20px 30px;
            background-color: var(--bg-darker);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        
        .stat-item {
            flex: 1;
            text-align: center;
            padding: 10px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
        }
        
        .actions-section {
            padding: 30px;
        }
        
        .shortlist-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .shortlist-btn:hover {
            background-color: rgba(255, 215, 0, 0.1);
            transform: translateY(-3px);
        }
        
        .shortlist-btn.remove {
            color: var(--error);
            border-color: var(--error);
            background-color: rgba(244, 67, 54, 0.05);
        }
        
        .shortlist-btn.remove:hover {
            background-color: rgba(244, 67, 54, 0.1);
        }
        
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary);
            color: var(--bg-darker);
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-left: 10px;
        }
        
        .contact-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        /* Media Queries */
        @media (max-width: 768px) {
            .service-content {
                grid-template-columns: 1fr;
            }
            
            .service-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .service-price {
                align-self: flex-start;
            }
            
            .cleaner-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .cleaner-avatar {
                margin: 0 auto;
            }
            
            .actions-section {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .shortlist-btn, .contact-btn {
                width: 100%;
                justify-content: center;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="service-details-container">
    <div class="service-card">
        <a href="CleanerProfile.php?id=<?= $service['cleanerid']; ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Cleaner Profile
        </a>

        <div class="service-header">
            <div class="service-title-section">
                <h1><?= htmlspecialchars($service['title']); ?></h1>
                <div class="service-meta">
                    <span class="service-category"><i class="fas fa-tag"></i> <?= htmlspecialchars($service['category']); ?></span>
                    <span><i class="fas fa-eye"></i> <?= $service['view_count']; ?> views</span>
                </div>
            </div>
            <div class="service-price"><?= $service['formatted_price']; ?></div>
        </div>
        
        <div class="service-content">
            <div class="service-image-container">
                <?php if ($service['image_path']): ?>
                    <img src="../../<?= htmlspecialchars($service['image_path']); ?>" alt="<?= htmlspecialchars($service['title']); ?>" class="service-image">
                <?php else: ?>
                    <i class="fas fa-image"></i>
                <?php endif; ?>
            </div>
            
            <div class="service-details">
                <h2><i class="fas fa-info-circle"></i> Description</h2>
                <div class="service-description">
                    <?= htmlspecialchars($service['description']); ?>
                </div>
                
                <div class="availability-section">
                    <h3><i class="fas fa-calendar-alt"></i> Availability</h3>
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
                            View Full Profile <i class="fas fa-external-link-alt"></i>
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
        
        <div class="actions-section">
            <form method="POST" style="display: inline;">
                <input type="hidden" name="toggle_shortlist" value="1">
                <button type="submit" class="shortlist-btn <?= $isShortlisted ? 'remove' : ''; ?>">
                    <i class="fas <?= $isShortlisted ? 'fa-bookmark-slash' : 'fa-bookmark'; ?>"></i> 
                    <?= $isShortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'; ?>
                </button>
            </form>
            
            <a href="CleanerProfile.php?id=<?= $cleaner['userid']; ?>" class="contact-btn">
                <i class="fas fa-envelope"></i> Contact Cleaner
            </a>
        </div>
    </div>
</div>

<script>
// Add smooth scroll behavior to back button
document.addEventListener('DOMContentLoaded', function() {
    const backButton = document.querySelector('.back-button');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            // Don't prevent default here to allow normal navigation
            // Just add a visual effect
            document.body.style.opacity = '0.8';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 300);
        });
    }
});
</script>

</body>
</html>