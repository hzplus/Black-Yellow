<!-- Cleaner Header -->
<div class="topbar">
  <div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    <span class="logo-text">Cleaner Portal</span>
  </div>
  <div class="user-info">
    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="../../logout.php" class="logout-link">Logout</a>
  </div>
</div>

<div class="navbar">
  <a href="cleanerDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'cleanerDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
  <a href="serviceListings.php" <?= basename($_SERVER['PHP_SELF']) == 'serviceListings.php' || strpos(basename($_SERVER['PHP_SELF']), 'service') !== false ? 'class="active"' : '' ?>>My Services</a>
  <a href="searchService.php" <?= basename($_SERVER['PHP_SELF']) == 'searchService.php' ? 'class="active"' : '' ?>>Search Service</a>
  <a href="createService.php" <?= basename($_SERVER['PHP_SELF']) == 'createService.php' ? 'class="active"' : '' ?>>Create Service</a>
  <a href="pastJobs.php" <?= basename($_SERVER['PHP_SELF']) == 'pastJobs.php' || (strpos(basename($_SERVER['PHP_SELF']), 'Job') !== false || strpos(basename($_SERVER['PHP_SELF']), 'job') !== false) ? 'class="active"' : '' ?>>Job History</a>
  <a href="searchPastJobs.php" <?= basename($_SERVER['PHP_SELF']) == 'searchPastJobs.php' ? 'class="active"' : '' ?>>Search Job History</a>
</div>  