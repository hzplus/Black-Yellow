<?php
session_start();
require_once __DIR__ . '/../../Controller/admin/viewUserController.php';

// Access control
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

// Ensure a user_id was provided
if (empty($_GET['user_id'])) {
    echo "No user specified.";
    exit();
}

$id   = (int)$_GET['user_id'];
$ctrl = new viewUserController();
$user = $ctrl->getUserById($id);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/admin-header.php'; ?>

<div class="dashboard-content">
    <h1>User Account Details</h1>
    
    <p><strong>User ID:</strong> <?= htmlspecialchars($user->userid) ?></p>
    <p><strong>Username:</strong> <?= htmlspecialchars($user->username) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user->role) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($user->status) ?></p>


    <br>
    <a href="userProfilesMenu.php"><button type="button">Back</button></a>
    </div>

</body>
</html>
    