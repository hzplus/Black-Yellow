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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .content-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .content-title {
            color: #FFD700;
            font-size: 28px;
            font-weight: bold;
        }
        
        .categories-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .categories-table th {
            background-color: #252525;
            color: #FFD700;
            font-weight: bold;
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #444;
        }
        
        .categories-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: #e0e0e0;
        }
        
        .categories-table tr:last-child td {
            border-bottom: none;
        }
        
        .categories-table tr:hover td {
            background-color: #252525;
        }
        
        .view-link {
            display: inline-block;
            padding: 8px 15px;
            background-color: #FFD700;
            color: #000;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .view-link:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #FFD700;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #e6c200;
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        .empty-message {
            text-align: center;
            padding: 30px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<div class="content-container">
    <div class="content-header">
        <h1 class="content-title">All Service Categories</h1>
    </div>

    <?php if (empty($categories)): ?>
        <div class="empty-message">No service categories found in the system.</div>
    <?php else: ?>
        <table class="categories-table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['categoryid']) ?></td>
                        <td><?= htmlspecialchars($category['name']) ?></td>
                        <td><?= isset($category['created_at']) ? htmlspecialchars($category['created_at']) : date('Y-m-d H:i:s') ?></td>
                        <td><a href="viewServiceCategoryDetails.php?category_id=<?= $category['categoryid'] ?>" class="view-link">View Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="categoriesMenu.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Categories Menu</a>
</div>

</body>
</html>