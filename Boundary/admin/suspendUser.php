<?php
session_start();
require_once '../../Controller/admin/suspendUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new suspendUserController();
$users = $controller->getActiveUsers();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_ids'])) {
        $userIds = $_POST['user_ids'];
        $success = $controller->suspend($userIds);
        $message = $success ? "✅ Selected user(s) suspended successfully." : "❌ Failed to suspend user(s).";
        $users = $controller->getActiveUsers(); // Refresh list
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

<!-- Content -->
<div class="dashboard-content">
    <h1>Suspend User Accounts</h1>
    <?php if ($message): ?>
        <p><?= $message ?></p>
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
                            <td><input type="checkbox" name="user_ids[]" value="<?= $user->userid ?>"></td>
                            <td><?= htmlspecialchars($user->userid) ?></td>
                            <td><?= htmlspecialchars($user->username) ?></td>
                            <td><?= htmlspecialchars($user->email) ?></td>
                            <td><?= htmlspecialchars($user->role) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <button type="submit">🛑Suspend Selected Users</button>
        <br>
    
        <a href="userAccountsMenu.php"><button type="button">← Back</button></a>
    </form>
</div>

</body>
</html>