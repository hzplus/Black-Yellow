<?php
session_start();
require_once '../../Controller/admin/searchUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new searchUserController();
$users = $controller->search(""); // Fetch all users
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Users</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    <a href="../../logout.php" class="logout-link">Logout</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="../../assets/images/logo.jpg" alt="Logo">
</div>

<!-- Navbar -->
<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
</div>

<!-- Page Content -->
<div class="dashboard-content">
    <h1>Search User Accounts</h1>
    <input type="text" id="searchBox" placeholder="Search by username or email..." onkeyup="filterTable()">

    <table border="1" cellpadding="10" cellspacing="0" id="userTable">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->userid) ?></td>
                    <td><?= htmlspecialchars($user->username) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                    <td><?= htmlspecialchars($user->role) ?></td>
                    <td><?= htmlspecialchars($user->status ?? 'active') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p id="noResultsMessage" style="display:none; color: red;">No matching users found.</p>
</div>

<script>
function filterTable() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const rows = document.querySelectorAll("#userTable tbody tr");
    let visible = 0;

    rows.forEach(row => {
        const username = row.cells[1].textContent.toUpperCase();
        const email = row.cells[2].textContent.toUpperCase();

        if (username.includes(input) || email.includes(input)) {
            row.style.display = "";
            visible++;
        } else {
            row.style.display = "none";
        }
    });

    document.getElementById('noResultsMessage').style.display = (visible === 0) ? "block" : "none";
}
</script>
<a href="userAccountsMenu.php"><button type="button">Back</button></a>

</body>
</html>
