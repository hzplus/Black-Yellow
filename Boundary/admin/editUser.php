<?php
session_start();
require_once '../../Controller/admin/searchUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new searchUserController();
$users = $controller->search(""); // Fetch all users initially
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Users</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=1.0">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Page Content -->
<div class="dashboard-content">
    <h1>Edit Users</h1>
    <input type="text" id="searchBox" placeholder="Search by username or email..." onkeyup="filterUsers()">

    <table border="1" cellpadding="10" cellspacing="0" id="userTable">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="6">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr class="user-row" data-username="<?= htmlspecialchars($user->username) ?>" data-email="<?= htmlspecialchars($user->email) ?>">
                        <td><?= htmlspecialchars($user->userid) ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= htmlspecialchars($user->role) ?></td>
                        <td><?= htmlspecialchars($user->status ?? 'active') ?></td>
                        <td><a href="editUserForm.php?user_id=<?= $user->userid ?>" class="button">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p id="noResultsMessage" style="display:none; color: red;">No matching users found.</p>

    <br>
    <a href="userAccountsMenu.php"><button type="button">Back</button></a>
</div>

<!-- Live Search -->
<script>
function filterUsers() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const rows = document.querySelectorAll("#userTable tbody .user-row");
    let matchCount = 0;

    rows.forEach(row => {
        const username = row.getAttribute("data-username").toUpperCase();
        const email = row.getAttribute("data-email").toUpperCase();

        if (username.includes(input) || email.includes(input)) {
            row.style.display = "";
            matchCount++;
        } else {
            row.style.display = "none";
        }
    });

    document.getElementById('noResultsMessage').style.display = (matchCount === 0) ? "block" : "none";
}
</script>

</body>
</html>

<style>
#searchBox {
    width: 300px;
    max-width: 100%;
}
</style>
