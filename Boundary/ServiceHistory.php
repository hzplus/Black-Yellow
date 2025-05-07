<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Controller/HomeownerController.php';

$controller = new HomeownerController();
$homeownerId = 1; // For now, hardcoded

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;
$history = $controller->getServiceHistory($homeownerId);

// Filter by date range
if ($from && $to) {
    $history = array_filter($history, function($entry) use ($from, $to) {
        $entryDate = strtotime($entry['datetime']);
        return $entryDate >= strtotime($from) && $entryDate <= strtotime($to);
    });
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service History</title>
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
    <h1>Service History</h1>

    <form method="GET">
        <label>From: <input type="date" name="from" value="<?= htmlspecialchars($from) ?>"></label>
        <label>To: <input type="date" name="to" value="<?= htmlspecialchars($to) ?>"></label>
        <button type="submit">Filter</button>
    </form>

    <?php foreach ($history as $entry): ?>
        <div class="card">
            <div>
                <img src="https://via.placeholder.com/100" alt="Cleaner">
            </div>
            <div>
                <p><strong>Date/Time:</strong> <?= $entry['datetime']; ?></p>
                <p><strong>Price Paid:</strong> $<?= $entry['price']; ?></p>
                <p><strong>Summary:</strong> <?= $entry['summary']; ?></p>
                <a href="ViewCleanerProfile.php?id=<?= $entry['cleaner_id']; ?>">View Profile</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>