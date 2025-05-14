<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/searchPastJobsController.php');

$cleanerId = $_SESSION['userid'];
$keyword = $_GET['keyword'] ?? '';
// $category = $_GET['category'] ?? null;
// $startDate = $_GET['start_date'] ?? null;
// $endDate = $_GET['end_date'] ?? null;

// $jobs = SearchPastJobsController::search($cleanerId, $keyword, $category, $startDate, $endDate);
$jobs = SearchPastJobsController::search($cleanerId, $keyword);
// $allCategories = ['All-in-one', 'Floor', 'Laundry', 'Toilet', 'Window'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Past Jobs</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        .filters { margin-bottom: 20px; }
        .filters label { margin-right: 10px; }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/cleaner-header.php'; ?>


<div class="dashboard">
    <h2>Search Past Jobs</h2>

    <form method="GET" class="filters">
        <label>Search:
            <input type="text" name="keyword" placeholder="Search by title or category..." value="<?= htmlspecialchars($keyword) ?>">
        </label>

        <button type="submit">Search</button>
        <a href="searchPastJobs.php" class="button" style="margin-left: 10px;">Clear</a>
    </form>

    <?php if (empty($jobs)): ?>
        <p>No matching past jobs found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Homeowner</th>
                    <th>Date Confirmed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?= htmlspecialchars($job['title']) ?></td>
                        <td><?= htmlspecialchars($job['category']) ?></td>
                        <td><?= htmlspecialchars($job['homeowner_name']) ?></td>
                        <td><?= date("Y-m-d", strtotime($job['booking_date'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
