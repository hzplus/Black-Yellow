<?php
require_once '../Controller/HomeownerController.php';

$controller = new HomeownerController();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;

$allCleaners = $controller->getCleaners();
$totalCleaners = count($allCleaners);
$totalPages = ceil($totalCleaners / $perPage);

$start = ($page - 1) * $perPage;
$cleaners = array_slice($allCleaners, $start, $perPage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Find a Cleaner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f9f9f9;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            position: relative;
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            display: flex;
            gap: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .details {
            flex: 1;
        }

        .view-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #2b8a3e;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .view-btn:hover {
            background-color: #237033;
        }

        .pagination {
            margin-top: 30px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #ddd;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #2b8a3e;
            color: white;
        }

        .pagination a:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>

    <h2>Find a Cleaner</h2>

    <div class="grid-container">
        <?php foreach ($cleaners as $cleaner): ?>
            <div class="card">
                <button class="view-btn" onclick="window.location.href='ViewCleanerProfile.php?id=<?= $cleaner->getId(); ?>'">View Profile</button>
                <img src="../assets/default_profile.png" alt="Profile Picture">
                <div class="details">
                    <h3><?= htmlspecialchars($cleaner->getName()); ?></h3>
                    <p><strong>Price:</strong> $<?= $cleaner->getPrice(); ?> / hr</p>
                    <p><strong>Availability:</strong> <?= htmlspecialchars($cleaner->getAvailability()); ?></p>
                    <p><strong>Services:</strong> <?= htmlspecialchars($cleaner->getServices()); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next »</a>
        <?php endif; ?>
    </div>

</body>
</html>