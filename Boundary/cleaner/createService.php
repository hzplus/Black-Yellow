<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<div class="navbar">
    <a href="cleanerDashboard.php">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="searchService.php">Search</a>
    <a href="../../logout.php">Logout</a>
</div>

<div class="dashboard">
    <h1>Create a New Service</h1>

    <form action="../../Controller/cleaner/createServiceController.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Service Title" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="number" name="price" placeholder="Price" step="0.01" required><br>
        <label>Available From:</label>
        <input type="date" name="available_from" required><br>
        <label>Available To:</label>
        <input type="date" name="available_to" required><br>
        <label>Category:</label>
        <select name="category" required>
            <option value="All-in-one">All-in-one</option>
            <option value="Floor">Floor</option>
            <option value="Laundry">Laundry</option>
            <option value="Toilet">Toilet</option>
            <option value="Window">Window</option>
        </select><br>
        <label>Upload Image:</label>
        <input type="file" name="image" accept="image/*"><br>
        <button type="submit">Create Service</button>
    </form>
</div>

</body>
</html>
