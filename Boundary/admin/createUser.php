<?php
session_start();
require_once '../../Controller/admin/createUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $message = "❌ Please fill in all required fields correctly.";
    } else {
        $controller = new createUserController();

        if ($controller->userExists($username, $email)) {
            $message = "❌ Account already exists.";
        } else {
            $success = $controller->createUser($username, $email, $password, $role);
            $message = $success ? "✅ User account created successfully." : "❌ Failed to create user account.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create User Account</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
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
  <div class="card">
    <h1>Create New User</h1>

    <?php if (!empty($message)): ?>
      <div class="<?= strpos($message, '❌') === 0 ? 'error' : 'message' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="form-grid">
      <div>
        <label for="role">User Role</label><br>
        <select id="role" name="role" required>
          <option value="">-- Select Role --</option>
          <option value="Admin" <?= ($_POST['role'] ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
          <option value="Cleaner" <?= ($_POST['role'] ?? '') === 'Cleaner' ? 'selected' : '' ?>>Cleaner</option>
          <option value="Homeowner" <?= ($_POST['role'] ?? '') === 'Homeowner' ? 'selected' : '' ?>>Homeowner</option>
          <option value="Manager" <?= ($_POST['role'] ?? '') === 'Manager' ? 'selected' : '' ?>>Manager</option>
        </select>
      </div>

      <div>
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
      </div>

      <div>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>

      <div>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="full-width">
        <button type="submit">➕ Create Account</button>
        <button type="button" onclick="location.href='userAccountsMenu.php'">🔙 Back</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
