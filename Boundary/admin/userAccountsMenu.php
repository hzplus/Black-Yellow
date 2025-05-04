<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Accounts Menu - Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Top bar -->
<div class="topbar">
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
</div>

<!-- Main content -->
<div class="dashboard-content">
    <h1>User Account Management</h1>
    <p>Select an action to perform:</p>

    <div class="dashboard-options">
        <button onclick="location.href='createUser.php'">➕ Create User Account</button>
        <button onclick="location.href='editUser.php'">✏️ Edit User</button>
        <button onclick="location.href='viewUser.php'">👁️ View All Users</button>
        <button onclick="location.href='searchUser.php'">🔍 Search User</button>
        <button onclick="location.href='suspendUser.php'">🛑 Suspend User</button>
    </div>
</div>

</body>
</html>
