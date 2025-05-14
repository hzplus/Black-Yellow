<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/viewServiceController.php');

if (!isset($_GET['serviceid'])) {
    echo "No service selected.";
    exit();
}

$serviceid = $_GET['serviceid'];
$service = ViewServiceController::getServiceDetails($serviceid);

if (!$service) {
    echo "Service not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/cleaner-header.php'; ?>

<div class="dashboard">
    <h2>Service Details</h2>
    <div class="service-detail-card">
        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
        <p><?php echo htmlspecialchars($service['description']); ?></p>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($service['price']); ?></p>
        <p><strong>Availability:</strong> <?php echo htmlspecialchars($service['availability']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($service['category']); ?></p>
        <?php if (!empty($service['image_path'])): ?>
            <img src="../../<?php echo htmlspecialchars($service['image_path']); ?>" width="200">
        <?php endif; ?>
        <p><strong>Views:</strong> <?= htmlspecialchars($service['view_count']) ?></p>
        <div style="margin-top: 20px;">
            <a href="editService.php?serviceid=<?= $serviceid ?>" class="button">Edit</a>
            <a href="removeService.php?serviceid=<?= $serviceid ?>" class="button" onclick="return confirm('Are you sure you want to delete this service?');">Remove</a>
        </div>

    </div>
</div>

</body>
</html>
