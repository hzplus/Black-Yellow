<?php
session_start();
if (!isset($_SESSION['view_service'])) {
    die("No service data found.");
}
$service = $_SESSION['view_service'];
unset($_SESSION['view_service']); // clear after use
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo">
    <div>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<div class="navbar">
    <a href="cleanerDashboard.php">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="#">History</a>
    <a href="../../logout.php">Logout</a>
</div>

<div class="dashboard" style="text-align:center;">
    <div style="background-color:#dcdcdc; padding:20px; width:400px; margin:auto; border-radius:6px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
            <a href="serviceListings.php"><button>Back</button></a>
            <div>
                <button disabled>View Count: <?php echo $service['view_count']; ?></button>
                <button disabled>Shortlist Count: <?php echo $service['shortlist_count']; ?></button>
            </div>
        </div>

        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
        <p><strong>Price:</strong> $<?php echo $service['price']; ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($service['description']); ?></p>

        <?php if ($service['image_path']) { ?>
            <img src="../../<?php echo htmlspecialchars($service['image_path']); ?>" alt="Service Image" style="width:200px; height:auto; margin:10px auto;">
        <?php } else { ?>
            <div style="width:200px; height:120px; background:white; margin:auto;">No Image</div>
        <?php } ?>

        <div style="margin-top:20px;">
            <button style="background-color:#FFD700; color:black;">Edit Service</button>
            <button style="background-color:#FFD700; color:black; ">Remove Service</button>
        </div>
    </div>
</div>

</body>
</html>
