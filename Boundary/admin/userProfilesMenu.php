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
    <title>User Profiles Menu - Admin</title>
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

<!-- Page Content -->
<div class="dashboard-content">
    <h1>User Profile Management</h1>
    <p>Select an action to perform:</p>

    <div class="dashboard-options">
        <button onclick="location.href='createUserProfile.php'">➕ Create User Profile</button>
        <button onclick="location.href='editUserProfiles.php'">✏️ Edit Profile</button>
        <button onclick="location.href='viewUserProfiles.php'">👁️ View Profiles</button>
        <button onclick="location.href='searchUserProfile.php'">🔍 Search Profile</button>
        <button onclick="location.href='suspendUserProfiles.php'" class="suspend">🛑 Suspend Profile</button>

    </div>
</div>

</body>
</html>
