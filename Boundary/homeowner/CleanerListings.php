<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include necessary files
require_once(__DIR__ . '/../../db/Database.php');
require_once(__DIR__ . '/../../Controller/homeowner/CleanerListingController.php');
require_once(__DIR__ . '/../../Controller/homeowner/ShortlistController.php');
require_once(__DIR__ . '/../../Controller/homeowner/HomeownerController.php');

// Ensure the homeowner ID is available from session
$homeownerId = $_SESSION['homeowner_id'] ?? $_SESSION['userid'] ?? 1;

// Create controllers
try {
    $homeownerController = new HomeownerController();
    
    // Handle add/remove to shortlist via bookmark icon toggle
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
        try {
            $homeownerController->toggleShortlist($_POST['toggle_id'], $homeownerId);
        } catch (Exception $e) {
            echo "Error toggling shortlist: " . $e->getMessage();
        }
    }

    // Handle search
    $searchTerm = $_GET['search'] ?? '';
    $filterType = $_GET['filter'] ?? 'name';

    // Get cleaners based on search
    try {
        if (!empty($searchTerm)) {
            if ($filterType === 'name') {
                $cleaners = $homeownerController->searchCleanersByName($searchTerm);
            } else {
                $cleaners = $homeownerController->searchCleanersByService($searchTerm);
            }
        } else {
            // Fetch all available cleaners if no search
            $cleaners = $homeownerController->getCleaners();
        }
    } catch (Exception $e) {
        echo "Error retrieving cleaners: " . $e->getMessage();
        $cleaners = [];
    }

    // Get shortlisted cleaners
    try {
        $shortlistedCleaners = $homeownerController->getShortlistedCleaners($homeownerId);
        
        // Extract shortlisted cleaner IDs for easier comparison
        $shortlistedIds = array_map(function($cleaner) {
            return $cleaner->getId();
        }, $shortlistedCleaners);
    } catch (Exception $e) {
        echo "Error retrieving shortlisted cleaners: " . $e->getMessage();
        $shortlistedIds = [];
    }
} catch (Exception $e) {
    echo "Controller initialization error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cleaner Listings</title>
  <link rel="stylesheet" href="/Black-Yellow/assets/css/style.css">
</head>
<body>
<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

  <!-- Search and Filter Form -->
  <form method="GET" class="filter-row">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchTerm ?? '') ?>">
    <select name="filter">
      <option value="name" <?= ($filterType ?? '') === 'name' ? 'selected' : '' ?>>By Name</option>
      <option value="services" <?= ($filterType ?? '') === 'services' ? 'selected' : '' ?>>By Services</option>
    </select>
    <button type="submit">Search</button>
  </form>

  <!-- Back Button (Moved below navbar) -->
  <a href="HomeownerDashboard.php" class="back-button">← Back</a>

  <h1 class="section-title">Available Cleaners</h1>

  <!-- Cleaner Cards Section in 2x2 Grid -->
  <div class="cleaner-grid">
    <?php if (empty($cleaners)): ?>
      <p>No cleaners found.</p>
    <?php else: ?>
      <?php foreach ($cleaners as $cleaner): ?>
        <div class="cleaner-card">
          <div class="card-header">
            <a href="ViewCleanerProfile.php?cleaner_id=<?= $cleaner->getId(); ?>" class="profile-link">View Profile</a>
          </div>
          <div class="card-body">
            <div class="card-left">
              <img src="/Black-Yellow/assets/cleaners/ben.jpg" alt="<?= htmlspecialchars($cleaner->getName()) ?>" class="cleaner-image">
            </div>
            <div class="card-right">
              <div class="card-info">
                <p class="cleaner-name">Name: <?= htmlspecialchars($cleaner->getName()) ?></p>
              </div>
              <div class="services-list">
                <h4>Services Offered:</h4>
                <?php if (!empty($cleaner->getServices())): ?>
                  <div class="cleaner-services">
                    <?php 
                    $services = $cleaner->getServices();
                    $displayCount = min(count($services), 3);
                    for ($i = 0; $i < $displayCount; $i++): 
                    ?>
                      <span class="service-tag"><?= htmlspecialchars($services[$i]->getTitle()) ?></span>
                    <?php endfor; ?>
                    <?php if (count($services) > 3): ?>
                      <span class="service-tag">+ <?= count($services) - 3 ?> more</span>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <p>No services listed</p>
                <?php endif; ?>
              </div>
              <form method="POST" class="shortlist-form">
                <input type="hidden" name="toggle_id" value="<?= $cleaner->getId() ?>">
                <button type="submit" class="shortlist-btn">
                  <?= in_array($cleaner->getId(), $shortlistedIds ?? []) ? 'Remove from Shortlist' : 'Add to Shortlist' ?>
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>