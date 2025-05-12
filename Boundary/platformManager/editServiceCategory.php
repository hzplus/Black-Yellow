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
        .action-link {
            color: blue;
            text-decoration: none;
        }
        .back-link {
            color: purple;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        #searchBox {
            margin-bottom: 10px;
            padding: 5px;
            width: 250px;
        }
        #noResultsMessage {
            color: red;
            display: none;
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
    <h1>Edit Service Categories</h1>
    
    <input type="text" id="searchBox" placeholder="Search by name..." onkeyup="filterCategories()">

    <table id="categoryTable">
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
                <td><a href="editServiceCategoryForm.php?category_id=<?= $category['categoryid'] ?>" class="action-link">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p id="noResultsMessage">No matching categories found.</p>
    
    <p><a href="categoriesMenu.php" class="back-link">← Back to Categories Menu</a></p>
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