<!-- Cleaner Header -->
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
    <a href="cleanerDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'cleanerDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
    <a href="serviceListings.php" <?= basename($_SERVER['PHP_SELF']) == 'serviceListings.php' || strpos(basename($_SERVER['PHP_SELF']), 'Service') !== false ? 'class="active"' : '' ?>>My Services</a>
    <a href="pastJobs.php" <?= basename($_SERVER['PHP_SELF']) == 'pastJobs.php' || strpos(basename($_SERVER['PHP_SELF']), 'Job') !== false ? 'class="active"' : '' ?>>Job History</a>
    <a href="createService.php" <?= basename($_SERVER['PHP_SELF']) == 'createService.php' ? 'class="active"' : '' ?>>Create Service</a>
</nav>