<?php
session_start();
require_once '../../Controller/admin/editUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['profile_id'])) {
    echo "No profile ID provided.";
    exit();
}

$controller = new editUserProfileController();
$profileId = (int)$_GET['profile_id'];
$profile = $controller->getProfileById($profileId);

if (!$profile) {
    echo "User profile not found.";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $success = $controller->updateProfile($profileId, $role, $description, $status);
    $message = $success ? "✅ Profile updated successfully!" : "❌ Failed to update profile.";

    // Refresh updated profile data
    $profile = $controller->getProfileById($profileId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navbar -->
<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
</div>

<!-- Main Content -->
<div class="dashboard-content">
    <h1>Edit User Profile: <?= htmlspecialchars($profile->role) ?></h1>

    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="role">Role:</label><br>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($profile->role) ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($profile->description) ?></textarea><br><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
        <option value="active" <?= $profile->status === 'active' ? 'selected' : '' ?>>active</option>
        <option value="suspended" <?= $profile->status === 'suspended' ? 'selected' : '' ?>>suspended</option>
        </select><br><br>

        <button type="submit">Update Profile</button>
        <button type="button" onclick="location.href='editUserProfiles.php'">🔙 Back</button>
    </form>
</div>

</body>
</html>
