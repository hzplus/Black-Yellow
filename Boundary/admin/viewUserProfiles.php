<?php
session_start();
require_once '../../Controller/admin/viewUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new viewUserProfileController();
$profiles = $controller->getAllProfiles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User Profiles</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Content -->
<div class="dashboard-content">
    <h1>All User Profiles</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Profile ID</th>
                <th>Role</th>
                <th>Description</th>
                <th>Status</th> 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profiles as $profile): ?>
                <tr>
                    <td><?= htmlspecialchars($profile->profile_id) ?></td>
                    <td><?= htmlspecialchars($profile->role) ?></td>
                    <td><?= htmlspecialchars($profile->description) ?></td>
                    <td><?= htmlspecialchars($profile->status) ?></td>
                    <td>
                    <a href="viewUserProfileDetails.php?profile_id=<?= $profile->profile_id ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="userProfilesMenu.php"><button type="button">Back</button></a>

</body>
</html>
