<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cleaner Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css"> <!-- adjust path as needed -->
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/cleaner-header.php'; ?>

<!-- Dashboard content -->
<div class="dashboard">
    <h1>Cleaner Dashboard</h1>
    <p>This is your central hub to manage services and jobs.</p>
</div>

</body>
</html>
