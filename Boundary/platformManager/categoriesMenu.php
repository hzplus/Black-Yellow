<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Categories Menu - Platform Manager</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Logo">
    </div>
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<!-- Navigation bar -->
<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<!-- Main content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Service Category Management</h1>
        <p style="text-align: center; margin-bottom: 30px;">Select an action to perform:</p>

        <div class="dashboard-options">
            <a href="createServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Create Category</h2>
                    <p>Add a new service category to the system.</p>
                </div>
            </a>
            
            <a href="viewServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>View Categories</h2>
                    <p>See all available service categories.</p>
                </div>
            </a>
            
            <a href="editServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Edit Categories</h2>
                    <p>Update existing service categories.</p>
                </div>
            </a>
            
            <a href="searchServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Search Categories</h2>
                    <p>Find specific service categories.</p>
                </div>
            </a>
            
            <a href="deleteServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Delete Category</h2>
                    <p>Remove obsolete service categories.</p>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>