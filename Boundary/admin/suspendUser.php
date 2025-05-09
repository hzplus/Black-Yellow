<?php
session_start();
require_once __DIR__ . '/../../Controller/admin/suspendUserController.php';

// Access control
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new suspendUserController();
$users      = $controller->getActiveUsers();
$message    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['user_ids'])) {
        $userIds = array_map('intval', $_POST['user_ids']);
        $success = $controller->suspendUsers($userIds);
        $message = $success
            ? "✅ Selected user(s) suspended successfully."
            : "❌ Failed to suspend user(s).";
        // Refresh the list
        $users = $controller->getActiveUsers();
    } else {
        $message = "❌ No users selected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suspend User Accounts</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
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

<!-- Main content -->
<div class="dashboard-content">
  <div class="card">
    <h1>Suspend User Accounts</h1>

    <?php if ($message): ?>
      <div class="<?= strpos($message, '❌') === 0 ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <table border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <th>Select</th>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
            <tr><td colspan="5" style="color:red;">No active users found.</td></tr>
          <?php else: ?>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><input type="checkbox" name="user_ids[]" value="<?= htmlspecialchars($user->userid) ?>"></td>
              <td><?= htmlspecialchars($user->userid) ?></td>
              <td><?= htmlspecialchars($user->username) ?></td>
              <td><?= htmlspecialchars($user->email) ?></td>
              <td><?= htmlspecialchars($user->role) ?></td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="full-width" style="margin-top:20px;">
        <button type="submit">🛑 Suspend Selected Users</button>
        <button type="button" onclick="location.href='userAccountsMenu.php'">🔙 Back</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
