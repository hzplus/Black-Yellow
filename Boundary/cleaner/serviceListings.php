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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Listings</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Include the header (topbar and navbar) -->
    <?php include '../../assets/includes/cleaner-header.php'; ?>

    <!-- Main Content -->
    <div class="dashboard-content container">
        <h1 class="dashboard-title">Your Services</h1>
        
        <!-- Service Grid -->
        <div class="grid-container">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="card" onclick="location.href='viewService.php?serviceid=<?= $service['serviceid'] ?>'">
                        <div class="card-header">
                            <h3 class="card-title"><?= htmlspecialchars($service['title']) ?></h3>
                        </div>
                        <div class="card-body">
                            <?php if(isset($service['description'])): ?>
                                <p><?= htmlspecialchars(substr($service['description'], 0, 100)) ?>...</p>
                            <?php else: ?>
                                <p class="text-muted">No description available.</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a href="viewService.php?serviceid=<?= $service['serviceid'] ?>" class="btn btn-sm">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <p>No services found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>