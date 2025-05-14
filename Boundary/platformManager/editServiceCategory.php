<?php
session_start();
require_once '../../Controller/platformManager/editServiceCategoryController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}

$controller = new EditServiceCategoryController();
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service Categories</title>
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
        
        .search-container {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #252525;
            border-radius: 8px;
            border: 1px solid #444;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 15px;
            background-color: #1a1a1a;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
            outline: none;
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
        
        .action-link {
            display: inline-block;
            padding: 8px 15px;
            background-color: #3498db;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .action-link:hover {
            background-color: #2980b9;
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
        
        .no-results {
            padding: 20px;
            text-align: center;
            color: #e57373;
            background-color: rgba(211, 47, 47, 0.1);
            border: 1px solid #d32f2f;
            border-radius: 5px;
            margin-top: 15px;
            display: none;
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<div class="content-container">
    <div class="content-header">
        <h1 class="content-title">Edit Service Categories</h1>
    </div>
    
    <div class="search-container">
        <input type="text" id="searchBox" class="search-input" placeholder="Search by name..." onkeyup="filterCategories()">
    </div>

    <table id="categoryTable" class="categories-table">
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
                    <td><a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="action-link">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p id="noResultsMessage" class="no-results">No matching categories found.</p>
    
    <a href="categoriesMenu.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Categories Menu</a>
</div>

<!-- Live Search Script -->
<script>
function filterCategories() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const table = document.getElementById("categoryTable");
    const rows = table.getElementsByTagName("tr");
    
    let visible = 0;

    // Start at index 1 to skip the header row
    for (let i = 1; i < rows.length; i++) {
        const nameCell = rows[i].getElementsByTagName("td")[1];
        if (nameCell) {
            const name = nameCell.textContent || nameCell.innerText;
            if (name.toUpperCase().indexOf(input) > -1) {
                rows[i].style.display = "";
                visible++;
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    document.getElementById('noResultsMessage').style.display = (visible === 0) ? "block" : "none";
}
</script>

</body>
</html>