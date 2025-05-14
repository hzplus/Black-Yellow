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
    $message = $success
        ? "✅ Profile updated successfully!"
        : "❌ Failed to update profile.";

    // Refresh updated profile data
    $profile = $controller->getProfileById($profileId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit User Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Main Content -->
<div class="dashboard-content">
  <div class="card">
    <h1>Edit User Profile: <?= htmlspecialchars($profile->role) ?></h1>

    <?php if (!empty($message)): ?>
      <div class="<?= strpos($message, '❌') === 0 ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="form-grid">
      <input type="hidden" name="profile_id" value="<?= htmlspecialchars($profile->profile_id) ?>">

      <div>
        <label for="role">Role</label><br>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($profile->role) ?>" required>
      </div>

      <div class="full-width">
        <label for="description">Description</label><br>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($profile->description) ?></textarea>
      </div>

      <div>
        <label for="status">Status</label><br>
        <select id="status" name="status" required>
          <option value="active" <?= $profile->status === 'active' ? 'selected' : '' ?>>Active</option>
          <option value="suspended" <?= $profile->status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>
      </div>

      <div class="full-width">
        <button type="submit">Update Profile</button>
        <button type="button" onclick="location.href='editUserProfiles.php'">🔙 Back</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
