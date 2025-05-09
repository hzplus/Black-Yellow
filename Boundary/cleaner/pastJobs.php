<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/pastJobsController.php');

$cleanerId = $_SESSION['userid'];
$category = $_GET['category'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$jobs = PastJobsController::fetchJobs($cleanerId, $category, $startDate, $endDate);
$allCategories = ['All-in-one', 'Floor', 'Laundry', 'Toilet', 'Window'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Past Jobs</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters label {
            margin-right: 10px;
        }
    </style>
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
    <a href="pastJobs.php">Past Jobs</a>
    <a href="../../logout.php">Logout</a>
</div>

<div class="dashboard">
    <h2>Confirmed Job History</h2>

    <form method="GET" class="filters">
        <label>Category:
            <select name="category">
                <option value="">All</option>
                <?php foreach ($allCategories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>From:
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
        </label>

        <label>To:
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
        </label>

        <button type="submit">Filter</button>
    </form>

    <?php if (empty($jobs)): ?>
        <p>No past jobs found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Homeowner ID</th>
                    <th>Date Confirmed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                    <tr>
                        <td><?= htmlspecialchars($job['title']) ?></td>
                        <td><?= htmlspecialchars($job['category']) ?></td>
                        <td><?= htmlspecialchars($job['homeownerid']) ?></td>
                        <td><?= date("Y-m-d", strtotime($job['confirmed_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
