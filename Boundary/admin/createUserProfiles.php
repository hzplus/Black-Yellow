<?php
session_start();
require_once __DIR__ . '/../../Controller/admin/createUserProfileController.php';

// Access control
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$ctrl    = new createUserProfileController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role        = trim($_POST['role'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $success = $ctrl->createProfile($role, $description);
    $message = $success
        ? "✅ Profile “{$role}” created successfully!"
        : "❌ Failed to create profile “{$role}”.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create User Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<div class="dashboard-content">
  <div class="card">
    <h1>Create New User Profile</h1>

    <?php if ($message): ?>
      <div class="<?= strpos($message, '❌') === 0 ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="form-grid">
      <div>
        <label for="role">Profile Role</label><br>
        <input
          type="text"
          id="role"
          name="role"
          value="<?= htmlspecialchars($_POST['role'] ?? '') ?>"
          required
        >
      </div>

      <div class="full-width">
        <label for="description">Description</label><br>
        <textarea
          id="description"
          name="description"
          rows="4"
          required
        ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="full-width">
        <button type="submit">➕ Create Profile</button>
        <button type="button" onclick="location.href='userProfilesMenu.php'">🔙 Back</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
