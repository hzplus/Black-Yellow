<?php
require_once '../Controller/HomeownerController.php';

$controller = new HomeownerController();
$cleaners = $controller->getShortlistedCleaners();

// Handle search
$searchTerm = $_GET['search'] ?? '';
$filterType = $_GET['filter'] ?? 'name';

if ($searchTerm) {
    $cleaners = array_filter($cleaners, function($cleaner) use ($searchTerm, $filterType) {
        if ($filterType === 'name') {
            return stripos($cleaner->getName(), $searchTerm) !== false;
        } else {
            return stripos($cleaner->getServices(), $searchTerm) !== false;
        }
    });
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shortlisted Cleaners</title>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <h1>Shortlisted Cleaners</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchTerm) ?>">
        <label><input type="radio" name="filter" value="name" <?= $filterType === 'name' ? 'checked' : '' ?>> Name</label>
        <label><input type="radio" name="filter" value="service" <?= $filterType === 'service' ? 'checked' : '' ?>> Service</label>
        <button type="submit">Filter</button>
    </form>

    <?php foreach ($cleaners as $cleaner): ?>
        <div class="card">
            <div>
                <img src="https://via.placeholder.com/100" alt="Cleaner">
            </div>
            <div>
                <p><strong>Name:</strong> <?= $cleaner->getName(); ?></p>
                <p><strong>Price:</strong> $<?= $cleaner->getPrice(); ?></p>
                <p><strong>Rating:</strong> <?= $cleaner->getRating(); ?>/5</p>
                <p><strong>Availability:</strong> <?= $cleaner->getAvailability(); ?></p>
                <a href="ViewCleanerProfile.php?id=<?= $cleaner->getId(); ?>">View Profile</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>