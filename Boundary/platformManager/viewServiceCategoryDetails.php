<?php
session_start();
require_once '../../Controller/platformManager/viewServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['category_id'])) {
    echo "Category ID not provided.";
    exit();
}

$controller = new ViewServiceCategoryController();
$category = $controller->getCategoryById($_GET['category_id']);

if (!$category) {
    echo "Category not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Details</title>
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
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table th {
            width: 150px;
            text-align: left;
            padding: 8px;
            border: 1px solid white;
            background-color: #000;
        }
        .details-table td {
            padding: 8px;
            border: 1px solid white;
        }
        .action-links {
            margin-top: 20px;
        }
        .action-links a {
            margin-right: 15px;
        }
        .edit-link {
            color: blue;
            text-decoration: none;
        }
        .delete-link {
            color: red;
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
    <h1>Service Category Details</h1>
    
    <table class="details-table">
        <tr>
            <th>Category ID</th>
            <td><?= htmlspecialchars($category['categoryid']) ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?= htmlspecialchars($category['name']) ?></td>
        </tr>
        <?php if (isset($category['description']) && !empty($category['description'])): ?>
        <tr>
            <th>Description</th>
            <td><?= htmlspecialchars($category['description']) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>Created At</th>
            <td><?= isset($category['created_at']) ? htmlspecialchars($category['created_at']) : date('Y-m-d H:i:s') ?></td>
        </tr>
        <?php if (isset($category['updated_at']) && $category['updated_at'] != $category['created_at']): ?>
        <tr>
            <th>Last Updated</th>
            <td><?= htmlspecialchars($category['updated_at']) ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="action-links">
        <a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="edit-link">Edit Category</a>
        <a href="deleteServiceCategoryConfirm.php?category_id=<?= $category['categoryid'] ?>" class="delete-link">Delete Category</a>
    </div>
    
    <p><a href="viewServiceCategory.php" class="back-link">← Back to Categories List</a></p>
</div>

</body>
</html> 