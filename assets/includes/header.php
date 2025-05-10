<!-- header.php -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Black&Yellow Logo" class="logo-img">
    </div>
    <div class="search-bar">
        <form action="searchcleaner.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search cleaner...">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="user-info">
        <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
        <a href="../../logout.php" class="logout">Logout</a>
    </div>
</div>

<!-- Navigation Bar -->
<nav class="navbar">
    <a href="HomeownerDashboard.php">Home</a>
    <a href="CleanerListings.php">Cleaner Listings</a>
    <a href="ServiceHistory.php">History</a>
    <a href="MyAccount.php">My Account</a>
</nav>