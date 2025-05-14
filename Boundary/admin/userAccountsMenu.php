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

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

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
