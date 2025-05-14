<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

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
        <th>Action</th> <!-- ✅ New column for Edit button -->
    </tr>
</thead>
<tbody>
    <?php if (empty($profiles)): ?>
        <tr><td colspan="5">No user profiles found.</td></tr> <!-- ✅ Updated colspan -->
    <?php else: ?>
        <?php foreach ($profiles as $profile): ?>
            <tr class="profile-row" data-role="<?= htmlspecialchars($profile->role) ?>">
                <td><?= htmlspecialchars($profile->profile_id) ?></td>
                <td><?= htmlspecialchars($profile->role) ?></td>
                <td><?= htmlspecialchars($profile->description) ?></td>
                <td><?= htmlspecialchars($profile->status) ?></td>
                <td>
                    <a href="editUserProfileForm.php?profile_id=<?= $profile->profile_id ?>" class="button">Edit</a>
                </td>
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

<style>
  
  #searchBox {
    width: 300px;
    max-width: 100%;
  }
</style>