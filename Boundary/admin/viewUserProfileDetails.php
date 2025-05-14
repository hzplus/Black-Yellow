<?php
session_start();
require_once '../../Controller/admin/viewUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['profile_id'])) {
    echo "No profile ID specified.";
    exit();
}

$controller = new viewUserProfileController();
$profile = $controller->getProfileById($_GET['profile_id']);

if (!$profile) {
    echo "Profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<div class="dashboard-content">
    <h1>Profile Details</h1>

    <p><strong>Profile ID:</strong> <?= htmlspecialchars($profile->profile_id) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($profile->role) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($profile->description) ?></p>

    <br>
    <a href="viewUserProfiles.php"><button type="button">Back</button></a>
</div>

</body>
</html>
