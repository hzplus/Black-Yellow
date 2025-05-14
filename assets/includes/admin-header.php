<!-- Admin Header -->
<div class="topbar">
  <div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    <span class="logo-text">Admin Portal</span>
  </div>
  <div class="user-info">
    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    <a href="../../logout.php" class="logout-link">Logout</a>
  </div>
</div>

<div class="navbar">
  <a href="adminDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'adminDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
  <a href="userAccountsMenu.php" <?= (basename($_SERVER['PHP_SELF']) == 'userAccountsMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'User') !== false) && strpos(basename($_SERVER['PHP_SELF']), 'Profile') === false ? 'class="active"' : '' ?>>User Accounts</a>
  <a href="userProfilesMenu.php" <?= basename($_SERVER['PHP_SELF']) == 'userProfilesMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'Profile') !== false ? 'class="active"' : '' ?>>User Profiles</a>
</div>