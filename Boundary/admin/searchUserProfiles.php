<?php
session_start();
require_once '../../Controller/admin/searchUserProfileController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$controller = new searchUserProfileController();
$profiles = $controller->search(""); // show all profiles initially
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search User Profiles</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<!-- Content -->
<div class="dashboard-content">
    <h1>Search User Profiles</h1>

    <input type="text" id="searchBox" placeholder="Search by role or description..." onkeyup="filterProfiles()">

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
            <?php foreach ($profiles as $profile): ?>
                <tr class="profile-row" data-role="<?= htmlspecialchars($profile->role) ?>" data-description="<?= htmlspecialchars($profile->description) ?>">
                    <td><?= htmlspecialchars($profile->profile_id) ?></td>
                    <td><?= htmlspecialchars($profile->role) ?></td>
                    <td><?= htmlspecialchars($profile->description) ?></td>
                    <td><?= htmlspecialchars($profile->status) ?></td> 
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p id="noResultsMessage" style="display:none; color: red;">No matching profiles found.</p>
</div>

<script>
function filterProfiles() {
    const input = document.getElementById("searchBox").value.toUpperCase();
    const rows = document.querySelectorAll(".profile-row");
    let found = 0;

    rows.forEach(row => {
        const role = row.dataset.role.toUpperCase();
        const desc = row.dataset.description.toUpperCase();
        const match = role.includes(input) || desc.includes(input);

        row.style.display = match ? "" : "none";
        if (match) found++;
    });

    document.getElementById("noResultsMessage").style.display = (found === 0) ? "block" : "none";
}
</script>
<a href="userProfilesMenu.php"><button type="button">Back</button></a>

</body>
</html>
<style>
  
  #searchBox {
    width: 300px;
    max-width: 100%;
  }
</style>
