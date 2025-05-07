<?php
// ViewCleanerProfile.php

require_once '../Controller/HomeownerController.php';
require_once '../Entity/Cleaner.php';

$cleaner = null;

if (isset($_GET['id'])) {
    $controller = new HomeownerController();
    $cleaner = $controller->getCleanerById((int)$_GET['id']);
}

if (!$cleaner) {
    echo "<p>Cleaner not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($cleaner->getName()); ?> - Profile</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .profile-container img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 100px;
            margin-bottom: 20px;
        }
        .profile-container h2 {
            margin-bottom: 10px;
        }
        .profile-container p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="../assets/default_profile.png" alt="Profile Picture">
        <h2><?= htmlspecialchars($cleaner->getName()); ?></h2>
        <p><strong>Price:</strong> $<?= $cleaner->getPrice(); ?> / hr</p>
        <p><strong>Availability:</strong> <?= htmlspecialchars($cleaner->getAvailability()); ?></p>
        <p><strong>Services:</strong> <?= htmlspecialchars($cleaner->getServices()); ?></p>
        <br>
        <a href="CleanerListings.php">← Back to Listings</a>
    </div>
</body>
</html>