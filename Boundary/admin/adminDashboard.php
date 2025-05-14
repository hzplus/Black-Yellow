<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">


</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Dashboard content -->
<div class="dashboard-content">
    <h1>Admin Dashboard</h1>
    <p>This is the main control panel for Admin users.</p>
</div>

</body>
</html>
