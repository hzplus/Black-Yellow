<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'PlatformManager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Category Menu - Platform Manager</title>
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
    <a href="platformManagerDashboard.php">Home</a>
    <a href="serviceCategoryMenu.php">Service Categories</a>
    <a href="generateReport.php">Reports</a>
</div>

<!-- Main content -->
<div class="dashboard-content">
    <h1>Service Category Management</h1>
    <p>Select an action to perform:</p>

    <div class="dashboard-options">
        <button onclick="location.href='createServiceCategory.php'">➕ Create Service Category</button>
        <button onclick="location.href='editServiceCategory.php'">✏️ Edit Service Category</button>
        <button onclick="location.href='viewServiceCategory.php'">👁️ View All Categories</button>
        <button onclick="location.href='searchServiceCategory.php'">🔍 Search Service Category</button>
        <button onclick="location.href='deleteServiceCategory.php'">🗑️ Delete Service Category</button>
    </div>
</div>

</body>
</html>