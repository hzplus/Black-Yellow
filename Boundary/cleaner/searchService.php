<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

$services = $_SESSION['search_results'] ?? [];
$keyword = $_SESSION['search_keyword'] ?? '';
unset($_SESSION['search_results'], $_SESSION['search_keyword']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Services</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<!-- Navbar -->
<div class="navbar">
    <a href="cleanerDashboard.php">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="createService.php">Create</a>
    <a href="../../logout.php">Logout</a>
</div>

<!-- Page Title -->
<div class="dashboard">
    <h1>Search Services</h1>

    <!-- Search Form -->
    <form action="../../Controller/cleaner/searchServiceController.php" method="POST">
        <input type="text" name="keyword" placeholder="Enter service title..." required value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Search Results -->
    <div class="service-cards">
        <?php if (!empty($services)) {
            foreach ($services as $service) { ?>
                <div class="service-card" onclick="location.href='../../Controller/cleaner/viewServiceController.php?serviceid=<?= $service['serviceid'] ?>'">
                    <?= htmlspecialchars($service['title']) ?>
                </div>
        <?php }} elseif (!empty($keyword)) { ?>
            <p>No services found for "<?php echo htmlspecialchars($keyword); ?>"</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
