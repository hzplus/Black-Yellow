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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .content-container {
            max-width: 900px;
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
        
        .category-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .category-header {
            background-color: #252525;
            padding: 20px;
            border-bottom: 2px solid #444;
        }
        
        .category-header h2 {
            color: #FFD700;
            margin: 0;
            font-size: 24px;
        }
        
        .category-id {
            color: #aaa;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .category-body {
            padding: 20px;
        }
        
        .category-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            color: #FFD700;
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #444;
            padding-bottom: 5px;
        }
        
        .section-content {
            color: #e0e0e0;
            line-height: 1.6;
        }
        
        .created-at,
        .updated-at {
            color: #aaa;
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .created-at i,
        .updated-at i {
            margin-right: 8px;
            color: #FFD700;
        }
        
        .action-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #444;
        }
        
        .action-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .action-button i {
            margin-right: 8px;
        }
        
        .edit-button {
            background-color: #3498db;
            color: #fff;
        }
        
        .edit-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .delete-button {
            background-color: #e53935;
            color: #fff;
        }
        
        .delete-button:hover {
            background-color: #c62828;
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
        
        .no-description {
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
        <h1 class="content-title">Service Category Details</h1>
    </div>
    
    <div class="category-card">
        <div class="category-header">
            <h2><?= htmlspecialchars($category['name']) ?></h2>
            <div class="category-id">ID: <?= htmlspecialchars($category['categoryid']) ?></div>
        </div>
        
        <div class="category-body">
            <?php if (isset($category['description']) && !empty($category['description'])): ?>
                <div class="category-section">
                    <h3 class="section-title">Description</h3>
                    <div class="section-content">
                        <?= htmlspecialchars($category['description']) ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="category-section">
                    <h3 class="section-title">Description</h3>
                    <div class="section-content no-description">
                        No description available for this category.
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="category-section">
                <h3 class="section-title">Metadata</h3>
                <div class="section-content">
                    <div class="created-at">
                        <i class="fas fa-calendar-plus"></i> Created: <?= isset($category['created_at']) ? htmlspecialchars($category['created_at']) : date('Y-m-d H:i:s') ?>
                    </div>
                    
                    <?php if (isset($category['updated_at']) && $category['updated_at'] != $category['created_at']): ?>
                        <div class="updated-at">
                            <i class="fas fa-calendar-check"></i> Last Updated: <?= htmlspecialchars($category['updated_at']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="action-links">
                <a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="action-button edit-button">
                    <i class="fas fa-edit"></i> Edit Category
                </a>
                <a href="deleteServiceCategoryConfirm.php?category_id=<?= $category['categoryid'] ?>" class="action-button delete-button">
                    <i class="fas fa-trash"></i> Delete Category
                </a>
            </div>
        </div>
    </div>
    
    <a href="viewServiceCategory.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Categories List</a>
</div>

</body>
</html>