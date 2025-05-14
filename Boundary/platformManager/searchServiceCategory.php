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
        
        .search-form {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            background-color: #252525;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #444;
        }
        
        .search-form input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            background-color: #1a1a1a;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-form input[type="text"]:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
            outline: none;
        }
        
        .search-form button {
            padding: 12px 25px;
            background-color: #FFD700;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-form button:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        
        .search-form .button {
            padding: 12px 25px;
            background-color: #333;
            color: #FFD700;
            border: 1px solid #FFD700;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-form .button:hover {
            background-color: #444;
            transform: translateY(-2px);
        }
        
        .search-icon {
            margin-right: 8px;
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
        
        .button {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-right: 8px;
        }
        
        .view-button {
            background-color: #3498db;
            color: #fff;
        }
        
        .view-button:hover {
            background-color: #2980b9;
        }
        
        .edit-button {
            background-color: #2ecc71;
            color: #fff;
        }
        
        .edit-button:hover {
            background-color: #27ae60;
        }
        
        .no-results {
            background-color: rgba(211, 47, 47, 0.1);
            border: 1px solid #d32f2f;
            color: #e57373;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
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
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<div class="content-container">
    <div class="content-header">
        <h1 class="content-title">Search Service Categories</h1>
    </div>

    <form method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="Search by name or description..." value="<?= htmlspecialchars($keyword) ?>" required>
        <button type="submit"><i class="fas fa-search search-icon"></i> Search</button>
        <a href="searchServiceCategory.php" class="button"><i class="fas fa-redo-alt search-icon"></i> Clear</a>
    </form>

    <?php if (!empty($keyword)): ?>
        <?php if (empty($categories)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i> No categories found matching: "<?= htmlspecialchars($keyword) ?>"
            </div>
        <?php else: ?>
            <table class="categories-table">
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
                                <a href="viewServiceCategoryDetails.php?category_id=<?= $category['categoryid'] ?>" class="button view-button">View</a>
                                <a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="button edit-button">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    
    <a href="categoriesMenu.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Categories Menu</a>
</div>

</body>
</html>