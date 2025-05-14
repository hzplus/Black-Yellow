<?php
session_start();
require_once __DIR__ . '/../../Controller/admin/editUserController.php';

// Access control
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new editUserController();

// Ensure we have a user_id
if (!isset($_GET['user_id'])) {
    echo "No user ID provided.";
    exit();
}

$userId = (int)$_GET['user_id'];
$user   = $controller->getUserById($userId);

if (!$user) {
    echo "User not found.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    $success = $controller->updateUser($userId, $username, $email, $role, $status);
    $message = $success
        ? "✅ User updated successfully!"
        : "❌ Failed to update user.";

    // Refresh user data
    $user = $controller->getUserById($userId);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit User – Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Main content -->
<div class="dashboard-content">
  <div class="card">
    <h1>Edit User: <?= htmlspecialchars($user->username) ?></h1>

    <?php if ($message): ?>
      <div class="<?= strpos($message, '❌') === 0 ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="form-grid">
      <!-- Keep the ID hidden -->
      <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->userid) ?>">

      <div>
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username"
               value="<?= htmlspecialchars($user->username) ?>" required>
      </div>

      <div>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($user->email) ?>" required>
      </div>

      <div>
        <label for="role">Role</label><br>
        <select id="role" name="role" required>
          <option value="Admin"     <?= $user->role === 'Admin'     ? 'selected' : '' ?>>Admin</option>
          <option value="Cleaner"   <?= $user->role === 'Cleaner'   ? 'selected' : '' ?>>Cleaner</option>
          <option value="Homeowner" <?= $user->role === 'Homeowner' ? 'selected' : '' ?>>Homeowner</option>
          <option value="Manager"   <?= $user->role === 'Manager'   ? 'selected' : '' ?>>Manager</option>
        </select>
      </div>

      <div>
        <label for="status">Status</label><br>
        <select id="status" name="status" required>
          <option value="active"    <?= $user->status === 'active'    ? 'selected' : '' ?>>Active</option>
          <option value="suspended" <?= $user->status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>
      </div>

      <div class="full-width">
        <button type="submit">Update User</button>
        <button type="button" onclick="location.href='userAccountsMenu.php'">🔙 Back</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
