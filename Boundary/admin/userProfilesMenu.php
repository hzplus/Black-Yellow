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

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Page Content -->
<div class="dashboard-content">
    <h1>User Profile Management</h1>
    <p>Select an action to perform:</p>

    <div class="dashboard-options">
        <button onclick="location.href='createUserProfiles.php'">➕ Create User Profile</button>
        <button onclick="location.href='editUserProfiles.php'">✏️ Edit Profile</button>
        <button onclick="location.href='viewUserProfiles.php'">👁️ View Profiles</button>
        <button onclick="location.href='searchUserProfiles.php'">🔍 Search Profile</button>
        <button onclick="location.href='suspendUserProfiles.php'" class="suspend">🛑 Suspend Profile</button>

    </div>
</div>

</body>
</html>
