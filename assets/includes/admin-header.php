<!-- Admin Header -->
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
    <a href="adminDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'adminDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
    <a href="userAccountsMenu.php" <?= basename($_SERVER['PHP_SELF']) == 'userAccountsMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'user') !== false ? 'class="active"' : '' ?>>User Accounts</a>
    <a href="userProfilesMenu.php" <?= basename($_SERVER['PHP_SELF']) == 'userProfilesMenu.php' || strpos(basename($_SERVER['PHP_SELF']), 'Profile') !== false ? 'class="active"' : '' ?>>User Profiles</a>
</nav>