<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/serviceNoOfViewsController.php');

if (!isset($_GET['serviceid'])) {
    echo "No service selected.";
    exit();
}

$serviceid = $_GET['serviceid'];
$views = ServiceNoOfViewsController::getServiceViews($serviceid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Views</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include '../../assets/includes/cleaner-header.php'; ?>

<div class="dashboard">
    <h2>View Count</h2>
    <p>This service has been viewed <strong><?= htmlspecialchars($views) ?></strong> times.</p>
    <br>
    <a href="viewService.php?serviceid=<?= $serviceid ?>" class="btn">← Back to Service</a>
</div>

</body>
</html>
