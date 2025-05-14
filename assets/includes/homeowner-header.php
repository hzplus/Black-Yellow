<!-- Homeowner Header -->
<div class="topbar">
  <div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    <span class="logo-text">Homeowner Portal</span>
  </div>
  <div class="search-container">
    <form action="CleanerListings.php" method="GET" class="search-form">
      <input type="text" name="search" placeholder="Find cleaners..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit">Search</button>
    </form>
  </div>
  <div class="user-info">
    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="../../logout.php" class="logout-link">Logout</a>
  </div>
</div>

<div class="navbar">
  <a href="HomeownerDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'HomeownerDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
  <a href="ViewCleanerListings.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewCleanerListings.php' || basename($_SERVER['PHP_SELF']) == 'CleanerListings.php' ? 'class="active"' : '' ?>>Find Cleaners</a>
  <a href="ViewShortlistedCleaners.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewShortlistedCleaners.php' || basename($_SERVER['PHP_SELF']) == 'ShortlistedCleaners.php' ? 'class="active"' : '' ?>>Shortlisted</a>
  <a href="ViewServiceHistory.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewServiceHistory.php' || basename($_SERVER['PHP_SELF']) == 'ServiceHistory.php' ? 'class="active"' : '' ?>>History</a>
  <a href="ViewMyAccount.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewMyAccount.php' || basename($_SERVER['PHP_SELF']) == 'MyAccount.php' ? 'class="active"' : '' ?>>My Account</a>
</div>