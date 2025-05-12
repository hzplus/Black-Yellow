<?php
session_start();
require_once '../../Controller/platformManager/viewServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new ViewServiceCategoryController();
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Service Categories</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
        }
        .header {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
        }
        .welcome-text {
            color: white;
        }
        .logout-link {
            color: purple;
            text-decoration: none;
            margin-left: 10px;
        }
        .logo-container {
            padding: 10px;
        }
        .logo {
            max-width: 100px;
        }
        .nav-bar {
            background-color: #FFD700;
            padding: 10px;
            text-align: center;
        }
        .nav-link {
            color: black;
            text-decoration: none;
            margin: 0 20px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        h1 {
            color: white;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid white;
        }
        th, td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #000;
            font-weight: bold;
        }
        .view-link {
            color: blue;
            text-decoration: none;
        }
        .back-link {
            color: purple;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="header">
    <span class="welcome-text">Welcome, manager!</span>
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<div class="logo-container">
    <a href="managerDashboard.php">
        <img src="../../assets/images/logo.jpg" alt="Logo" class="logo">
    </a>
</div>

<div class="nav-bar">
    <a href="managerDashboard.php" class="nav-link">Home</a>
    <a href="categoriesMenu.php" class="nav-link">Service Categories</a>
    <a href="reportsMenu.php" class="nav-link">Reports</a>
</div>

<div class="content">
    <h1>All Service Categories</h1>

    <table>
        <tr>
            <th>Category ID</th>
            <th>Name</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category['categoryid']) ?></td>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td><?= isset($category['created_at']) ? htmlspecialchars($category['created_at']) : date('Y-m-d H:i:s') ?></td>
                <td><a href="viewServiceCategoryDetails.php?category_id=<?= $category['categoryid'] ?>" class="view-link">View</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="categoriesMenu.php" class="back-link">← Back to Categories Menu</a></p>
</div>

</body>
</html>