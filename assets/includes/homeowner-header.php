<!-- Homeowner Header -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo">
    </div>
    <div class="search-form">
        <form action="CleanerListings.php" method="get">
            <input type="text" name="search" class="form-control" placeholder="Search cleaners...">
            <button type="submit" class="btn">Search</button>
        </form>
    </div>
    <div class="user-info">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../../logout.php" class="btn btn-sm">Logout</a>
    </div>
</div>

<nav class="navbar">
    <a href="HomeownerDashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'HomeownerDashboard.php' ? 'class="active"' : '' ?>>Dashboard</a>
    <a href="ViewCleanerListings.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewCleanerListings.php' || strpos(basename($_SERVER['PHP_SELF']), 'Cleaner') !== false ? 'class="active"' : '' ?>>Find Cleaners</a>
    <a href="ViewShortlistedCleaners.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewShortlistedCleaners.php' ? 'class="active"' : '' ?>>Shortlisted</a>
    <a href="ViewServiceHistory.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewServiceHistory.php' ? 'class="active"' : '' ?>>History</a>
    <a href="ViewMyAccount.php" <?= basename($_SERVER['PHP_SELF']) == 'ViewMyAccount.php' ? 'class="active"' : '' ?>>My Account</a>
</nav>