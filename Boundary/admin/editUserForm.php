<?php
session_start();
require_once '../../Controller/admin/editUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new editUserController();

if (!isset($_GET['user_id'])) {
    echo "No user ID provided.";
    exit();
}

$userId = $_GET['user_id'];
$user = $controller->getUserById($userId);

if (!$user) {
    echo "User not found.";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    $success = $controller->updateUser($userId, $username, $email, $role, $status);
    $message = $success ? "✅ User updated successfully!" : "❌ Failed to update user.";
    $user = $controller->getUserById($userId); // Refresh data
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Details</title>
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
    <h1>Edit User: <?= htmlspecialchars($user['username']) ?></h1>
    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
            <option value="Cleaner" <?= $user['role'] === 'Cleaner' ? 'selected' : '' ?>>Cleaner</option>
            <option value="Homeowner" <?= $user['role'] === 'Homeowner' ? 'selected' : '' ?>>Homeowner</option>
            <option value="Manager" <?= $user['role'] === 'Manager' ? 'selected' : '' ?>>Manager</option>
        </select>

        <label>Status:</label>
        <select name="status" required>
            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>

        <br><br>
        <button type="submit">Update User</button>
        <a href="editUser.php"><button type="button">Back</button></a>
    </form>
</div>

</body>
</html>
