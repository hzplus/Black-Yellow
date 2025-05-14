<!-- Manager Header -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    </div>
    <div class="user-info">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../../logout.php" class="btn btn-sm">Logout</a>
    </div>
</div>

<nav class="navbar">
    <a href="managerDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'managerDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
    <a href="categoriesMenu.php" <?= basename($_SERVER['PHP_SELF']) == 'categoriesMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'Category') !== false ? 'class="active"' : '' ?>>Service Categories</a>
    <a href="reportsMenu.php" <?= basename($_SERVER['PHP_SELF']) == 'reportsMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'Report') !== false ? 'class="active"' : '' ?>>Reports</a>
</nav>