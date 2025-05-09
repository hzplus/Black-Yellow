<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/serviceController.php');

$cleanerId = $_SESSION['userid'];
$services = ServiceController::getCleanerServices($cleanerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Listings</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="cleanerDashboard.php">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="searchService.php">Search</a>
    <a href="createService.php">Create New Service</a>
    <a href="../../logout.php">Logout</a>
</div>

<!-- Services Section -->
<div class="services-section">
    <h2>Your Services</h2>
    <div class="service-cards">
        <?php if (!empty($services)) {
            foreach ($services as $service) { ?>
                <div class="service-card" onclick="location.href='viewService.php?serviceid=<?= $service['serviceid'] ?>'">
                    <?= htmlspecialchars($service['title']) ?>
                </div>
        <?php }} else { ?>
            <p>No services found.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
