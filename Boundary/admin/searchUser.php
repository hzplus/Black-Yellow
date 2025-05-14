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
    <title>Search User Accounts</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Content -->
<div class="dashboard-content">
    <h1>Search User Accounts</h1>

    <input type="text" id="searchBox" placeholder="Search by username or email..." onkeyup="filterUsers()">

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
                <tr class="user-row" data-username="<?= htmlspecialchars($user->username) ?>" data-email="<?= htmlspecialchars($user->email) ?>">
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
function filterUsers() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const rows = document.querySelectorAll(".user-row");
    let found = 0;

    rows.forEach(row => {
        const username = row.dataset.username.toUpperCase();
        const email = row.dataset.email.toUpperCase();
        const match = username.includes(input) || email.includes(input);

        row.style.display = match ? "" : "none";
        if (match) found++;
    });

    document.getElementById("noResultsMessage").style.display = (found === 0) ? "block" : "none";
}
</script>

<a href="userAccountsMenu.php"><button type="button">Back</button></a>

</body>
</html>

<style>
#searchBox {
    width: 300px;
    max-width: 100%;
}
</style>
