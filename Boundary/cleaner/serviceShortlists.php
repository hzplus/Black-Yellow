<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/serviceShortlistsController.php');

if (!isset($_GET['serviceid'])) {
    echo "No service selected.";
    exit();
}

$serviceid = $_GET['serviceid'];
$shortlists = ServiceShortlistsController::getServiceShortlists($serviceid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Shortlists</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<?php include '../../assets/includes/cleaner-header.php'; ?>

<div class="dashboard">
    <h2>Shortlist Count</h2>
    <p>This service has been shortlisted <strong><?= htmlspecialchars($shortlists) ?></strong> times.</p>
    <br>
    <a href="viewService.php?serviceid=<?= $serviceid ?>" class="btn">← Back to Service</a>
</div>

</body>
</html>
