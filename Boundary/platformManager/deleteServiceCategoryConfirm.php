<?php
// This file should be saved as deleteServiceCategoryConfirm.php in the same directory as your other manager boundary files
// For example: /Boundary/platformManager/deleteServiceCategoryConfirm.php

session_start();
require_once '../../Controller/platformManager/viewServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['category_id'])) {
    header("Location: viewServiceCategory.php?error=missing_id");
    exit();
}

$controller = new ViewServiceCategoryController();
$category = $controller->getCategoryById($_GET['category_id']);

if (!$category) {
    header("Location: viewServiceCategory.php?error=not_found");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Category Confirmation</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .confirm-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #111;
            border: 2px solid #FFD700;
            border-radius: 10px;
            text-align: center;
        }
        
        .warning-icon {
            font-size: 60px;
            color: #FFD700;
            margin-bottom: 20px;
        }
        
        .confirm-title {
            font-size: 24px;
            color: #FFD700;
            margin-bottom: 20px;
        }
        
        .confirm-message {
            font-size: 16px;
            color: #FFF;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .category-name {
            font-weight: bold;
            color: #FFD700;
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .delete-btn {
            background-color: #e53935;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }
        
        .delete-btn:hover {
            background-color: #c62828;
        }
        
        .cancel-btn {
            background-color: #333;
            color: white;
            border: 1px solid #555;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }
        
        .cancel-btn:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    </div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<div class="confirm-container">
    <div class="warning-icon">
        <i class="fas fa-exclamation-triangle"></i>
    </div>
    
    <h2 class="confirm-title">Delete Category Confirmation</h2>
    
    <p class="confirm-message">
        Are you sure you want to delete the category <span class="category-name"><?= htmlspecialchars($category['name']) ?></span>?<br>
        This action cannot be undone.
    </p>
    
    <div class="button-group">
        <a href="deleteServiceCategory.php?category_id=<?= $category['categoryid'] ?>" class="delete-btn">
            <i class="fas fa-trash"></i> Yes, Delete
        </a>
        <a href="viewServiceCategory.php" class="cancel-btn">
            <i class="fas fa-times"></i> Cancel
        </a>
    </div>
</div>

</body>
</html>