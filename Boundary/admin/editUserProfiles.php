<?php
session_start();
require_once '../../Controller/admin/editUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new editUserProfileController();
$profiles = $controller->getAllProfiles(); // fetch all user profiles
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Profiles</title>
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

<!-- Content -->
<div class="dashboard-content">
    <h1>Edit User Profiles</h1>
    <input type="text" id="searchBox" placeholder="Search by role..." onkeyup="filterProfiles()">

    <table border="1" cellpadding="10" cellspacing="0" id="profileTable">
        <thead>
            <tr>
                <th>Profile ID</th>
                <th>Role</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($profiles)): ?>
                <tr><td colspan="4">No user profiles found.</td></tr>
            <?php else: ?>
                <?php foreach ($profiles as $profile): ?>
                    <tr class="profile-row" data-role="<?= htmlspecialchars($profile->role) ?>">
                        <td><?= htmlspecialchars($profile->profileId) ?></td>
                        <td><?= htmlspecialchars($profile->role) ?></td>
                        <td><?= htmlspecialchars($profile->description) ?></td>
                        <td><?= htmlspecialchars($profile->status) ?></td>
                        <td><a href="editUserProfileForm.php?profile_id=<?= $profile->profileId ?>" class="button">Edit</a></td>
                        
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p id="noResultsMessage" style="display:none; color: red;">No matching profiles found.</p>
</div>

<script>
function filterProfiles() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const rows = document.querySelectorAll("#profileTable tbody .profile-row");
    let matchCount = 0;

    rows.forEach(row => {
        const role = row.getAttribute("data-role").toUpperCase();
        if (role.includes(input)) {
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
<a href="userProfilesMenu.php"><button type="button">Back</button></a>

</html>
