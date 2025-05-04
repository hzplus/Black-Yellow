<?php
session_start();
require_once '../../Controller/admin/suspendUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new suspendUserProfileController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_ids'])) {
    $controller->suspend($_POST['profile_ids']);
    header("Location: suspendUserProfiles.php?success=1");
    exit();
}

$profiles = $controller->getActiveProfiles();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Suspend User Profiles</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
</div>

<div class="dashboard-content">
    <h1>Suspend User Profiles</h1>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">✅ Selected profiles suspended successfully.</p>
    <?php endif; ?>

    <form method="POST">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Profile ID</th>
                    <th>Role</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($profiles)): ?>
                    <tr><td colspan="5">No active profiles found.</td></tr>
                <?php else: ?>
                    <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td><input type="checkbox" name="profile_ids[]" value="<?= $profile->profileId ?>"></td>
                            <td><?= htmlspecialchars($profile->profileId) ?></td>
                            <td><?= htmlspecialchars($profile->role) ?></td>
                            <td><?= htmlspecialchars($profile->description) ?></td>
                            <td><?= htmlspecialchars($profile->status) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <button type="submit">🛑 Suspend Selected Profiles</button>
    </form>

    <br>
    <a href="userProfilesMenu.php"><button type="button">Back</button></a>
    </div>

</body>
</html>
