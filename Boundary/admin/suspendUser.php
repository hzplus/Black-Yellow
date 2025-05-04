<?php
session_start();
require_once '../../controller/admin/suspendUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new suspendUserController();
$users = $controller->getActiveUsers();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userIds = $_POST['user_id'];
    $success = $controller->suspend($userIds);
    $message = $success ? "✅ Selected users have been suspended." : "❌ Suspension failed.";
    // Refresh user list after update
    $users = $controller->getActiveUsers();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Suspend User Accounts</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!
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

<!-- Content -->
<div class="dashboard-content">
    <h1>Suspend User Accounts</h1>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <input type="text" id="searchBox" class="search-input" placeholder="Search by name or email..." onkeyup="filterUsers()">

    <form method="POST">
        <div id="userSelectionForm">
            <?php if (empty($users)): ?>
                <p>No active users available.</p>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <div class="user-entry" data-user-name="<?= htmlspecialchars($user->username) ?>" data-email="<?= htmlspecialchars($user->email) ?>">
                        <input type="checkbox" name="user_id[]" value="<?= $user->userid ?>" id="user<?= $user->userid ?>">
                        <label for="user<?= $user->userid ?>">
                            <?= htmlspecialchars($user->username) ?> (<?= htmlspecialchars($user->email) ?>)
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <br>
        <button type="submit">🛑 Suspend Selected Users</button>
    </form>

    <p id="noResultsMessage" class="no-results" style="display:none;">No matching user accounts found.</p>
</div>

<!-- JS Search -->
<script>
function filterUsers() {
    const input = document.getElementById('searchBox').value.toUpperCase();
    const entries = document.querySelectorAll('.user-entry');
    let matchCount = 0;

    entries.forEach(entry => {
        const name = entry.dataset.userName.toUpperCase();
        const email = entry.dataset.email.toUpperCase();

        if (name.includes(input) || email.includes(input)) {
            entry.style.display = "";
            matchCount++;
        } else {
            entry.style.display = "none";
        }
    });

    const message = document.getElementById('noResultsMessage');
    message.style.display = (input !== "" && matchCount === 0) ? "block" : "none";
}
</script>

</body>
</html>
