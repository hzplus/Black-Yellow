<?php
session_start();
require_once '../../Controller/admin/editUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new editUserProfileController();

if (!isset($_GET['profile_id'])) {
    echo "No profile ID provided.";
    exit();
}

$profileId = $_GET['profile_id'];
$profile = $controller->getProfileById($profileId);

if (!$profile) {
    echo "User profile not found.";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $description = $_POST['description'];

    $success = $controller->updateProfile($profileId, $role, $description);
    $message = $success ? "✅ Profile updated successfully!" : "❌ Failed to update profile.";
    $profile = $controller->getProfileById($profileId); // Refresh updated info
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Profile</title>
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
    <h1>Edit User Profile: <?= htmlspecialchars($profile->role) ?></h1>

    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Role:</label>
        <input type="text" name="role" value="<?= htmlspecialchars($profile->role) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($profile->description) ?></textarea>

        <br><br>
        <button type="submit">Update Profile</button>
        <a href="editUserProfiles.php"><button type="button">Back</button></a>

    </form>
</div>
        <a href="userProfilesMenu.php"><button type="button">Back</button></a>

</body>
</html>
