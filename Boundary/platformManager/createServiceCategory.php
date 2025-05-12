<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../Controller/platformManager/createServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Exception Flow: Validate input
    if (empty($name)) {
        $message = "❌ Please enter a category name.";
    } else {
        $controller = new CreateServiceCategoryController();

        // Alternate Flow: Duplicate check
        if ($controller->categoryExists($name)) {
            $message = "❌ A category with this name already exists.";
        } else {
            // Normal Flow: Create category
            $success = $controller->createCategory($name, $description);
            $message = $success ? "✅ Service category created successfully." : "❌ Failed to create service category.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Service Category</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .form-container {
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #333;
            border: 1px solid #FFD700;
            border-radius: 5px;
            color: #FFF;
        }
        
        .form-container label {
            display: block;
            margin-bottom: 5px;
            color: #FFD700;
        }
        
        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .form-buttons button[type="submit"] {
            background-color: #FFD700;
            color: #000;
        }
        
        .form-buttons button[type="button"] {
            background-color: #333;
            color: #FFD700;
            border: 1px solid #FFD700;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="Logo">
    </div>
    <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
    <a href="../../logout.php" class="logout">Logout</a>
</div>

<!-- Navigation -->
<div class="navbar">
    <a href="managerDashboard.php">Home</a>
    <a href="categoriesMenu.php">Service Categories</a>
    <a href="reportsMenu.php">Reports</a>
</div>

<!-- Page Content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Create New Service Category</h1>

        <?php if (!empty($message)): ?>
            <div class="message" style="background-color: <?= strpos($message, '✅') !== false ? 'rgba(46, 125, 50, 0.2)' : 'rgba(211, 47, 47, 0.2)' ?>; color: <?= strpos($message, '✅') !== false ? '#81c784' : '#e57373' ?>; border: 1px solid <?= strpos($message, '✅') !== false ? '#2e7d32' : '#d32f2f' ?>;">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST">
                <label>Category Name:</label>
                <input type="text" name="name" required>

                <label>Description:</label>
                <textarea name="description" rows="4"></textarea>

                <div class="form-buttons">
                    <button type="submit">Create Category</button>
                    <a href="categoriesMenu.php"><button type="button">Back</button></a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>