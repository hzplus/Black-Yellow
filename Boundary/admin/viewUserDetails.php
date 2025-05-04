<?php
session_start();
require_once '../../Controller/admin/viewUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$controller = new viewUserController();
$user = $controller->getUserById($_GET['user_id']);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
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
    <h1>User Account Details</h1>
    <p><strong>User ID:</strong> <?= htmlspecialchars($user['userid']) ?></p>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($user['status']) ?></p>

    <br>
    <a href="userProfilesMenu.php"><button type="button">Back</button></a>
    </div>

</body>
</html>
