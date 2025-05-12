<?php
session_start();
require_once '../../Controller/platformManager/editServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new EditServiceCategoryController();

if (!isset($_GET['category_id'])) {
    echo "No category ID provided.";
    exit();
}

$categoryId = $_GET['category_id'];
$category = $controller->getCategoryById($categoryId);

if (!$category) {
    echo "Category not found.";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($name)) {
        $message = "❌ Category name cannot be empty.";
    } else if ($controller->categoryExistsByName($name, $categoryId)) {
        $message = "❌ Another category with this name already exists.";
    } else {
        $success = $controller->updateCategory($categoryId, $name, $description);
        $message = $success ? "✅ Category updated successfully!" : "❌ Failed to update category.";
        $category = $controller->getCategoryById($categoryId); // Refresh data
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service Category</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"], 
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group button {
            padding: 8px 15px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div style="text-align: right; padding-right: 20px;">
    Welcome, manager! 
    <a href="../../logout.php" style="color: purple; text-decoration: none;">Logout</a>
</div>

<div style="padding: 10px;">
    <a href="managerDashboard.php">
        <img src="../../assets/images/logo.jpg" alt="Logo" style="max-width: 100px;">
    </a>
</div>

<div style="background-color: #FFD700; padding: 10px; text-align: center;">
    <a href="managerDashboard.php" style="color: black; text-decoration: none; margin: 0 20px;">Home</a>
    <a href="categoriesMenu.php" style="color: black; text-decoration: none; margin: 0 20px;">Service Categories</a>
    <a href="reportsMenu.php" style="color: black; text-decoration: none; margin: 0 20px;">Reports</a>
</div>

<div style="padding: 20px;">
    <h1>Edit Category: <?= htmlspecialchars($category['name']) ?></h1>
    
    <?php if ($message): ?>
        <div style="padding: 10px; margin-bottom: 15px; background-color: <?= strpos($message, '✅') !== false ? '#d4edda' : '#f8d7da' ?>; color: <?= strpos($message, '✅') !== false ? '#155724' : '#721c24' ?>; border: 1px solid <?= strpos($message, '✅') !== false ? '#c3e6cb' : '#f5c6cb' ?>; border-radius: 5px;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        </div>

        <?php if (isset($category['description'])): ?>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
        </div>
        <?php endif; ?>

        <div class="button-group">
            <button type="submit">Update Category</button>
            <a href="editServiceCategory.php"><button type="button">Back</button></a>
        </div>
    </form>
</div>

</body>
</html>