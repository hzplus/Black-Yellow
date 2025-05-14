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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .dashboard-box {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        
        .dashboard-title {
            color: #FFD700;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }
        
        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }
        
        .message.success {
            background-color: rgba(46, 125, 50, 0.2);
            color: #81c784;
            border: 1px solid #2e7d32;
        }
        
        .message.error {
            background-color: rgba(211, 47, 47, 0.2);
            color: #e57373;
            border: 1px solid #d32f2f;
        }
        
        .form-container {
            background-color: #252525;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #444;
        }
        
        .form-container label {
            display: block;
            color: #FFD700;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 25px;
            background-color: #1a1a1a;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-container input[type="text"]:focus,
        .form-container textarea:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
            outline: none;
        }
        
        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .form-buttons button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-buttons button[type="submit"] {
            background-color: #FFD700;
            color: #000;
        }
        
        .form-buttons button[type="submit"]:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        
        .form-buttons button[type="button"] {
            background-color: #333;
            color: #FFD700;
            border: 1px solid #FFD700;
        }
        
        .form-buttons button[type="button"]:hover {
            background-color: #444;
            transform: translateY(-2px);
        }
        
        .form-icon {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-icon i {
            font-size: 48px;
            color: #FFD700;
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<!-- Page Content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Create New Service Category</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <div class="form-icon">
                <i class="fas fa-tag"></i>
            </div>
            
            <form method="POST">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter category name">

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" placeholder="Provide a detailed description of this service category"></textarea>

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