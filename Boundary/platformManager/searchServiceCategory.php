<?php
session_start();
require_once '../../Controller/platformManager/searchServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new SearchServiceCategoryController();
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$categories = [];

if (!empty($keyword)) {
    $categories = $controller->search($keyword);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Service Categories</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navigation -->
<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<!-- Content -->
<div class="dashboard-content">
    <h1>Search Service Categories</h1>

    <form method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="Search by name or description..." value="<?= htmlspecialchars($keyword) ?>" required>
        <button type="submit">Search</button>
        <a href="searchServiceCategory.php" class="button">Clear</a>
    </form>

    <?php if (!empty($keyword)): ?>
        <?php if (empty($categories)): ?>
            <p>No categories found matching: "<?= htmlspecialchars($keyword) ?>"</p>
        <?php else: ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Name</th>
                        <?php if (isset($categories[0]['description'])): ?>
                        <th>Description</th>
                        <?php endif; ?>
                        <?php if (isset($categories[0]['created_at'])): ?>
                        <th>Created At</th>
                        <?php endif; ?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['categoryid']) ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <?php if (isset($category['description'])): ?>
                            <td><?= htmlspecialchars($category['description'] ?? 'No description') ?></td>
                            <?php endif; ?>
                            <?php if (isset($category['created_at'])): ?>
                            <td><?= htmlspecialchars($category['created_at']) ?></td>
                            <?php endif; ?>
                            <td>
                                <a href="viewServiceCategoryDetails.php?category_id=<?= $category['categoryid'] ?>" class="button">View</a>
                                <a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="button">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>