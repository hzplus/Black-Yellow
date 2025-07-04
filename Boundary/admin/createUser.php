<?php
session_start();
require_once '../../Controller/admin/createUserController.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    // Exception Flow: Validate input
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $message = "❌ Please fill in all required fields correctly.";
    } else {
        $controller = new createUserController();

        // Alternate Flow: Duplicate check
        if ($controller->userExists($username, $email)) {
            $message = "❌ Account already exists.";
        } else {
            // Normal Flow: Create user
            $success = $controller->createUser($username, $email, $password, $role);
            $message = $success ? "✅ User account created successfully." : "❌ Failed to create user account.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User Account</title>
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

<!-- Navigation -->
<div class="navbar">
    <a href="adminDashboard.php">Home</a>
    <a href="userAccountsMenu.php">User Accounts</a>
    <a href="userProfilesMenu.php">User Profiles</a>
</div>

<!-- Page Content -->
<div class="dashboard-content">
    <h1>Create New User</h1>

    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <label>User Role:</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="Admin">Admin</option>
            <option value="Cleaner">Cleaner</option>
            <option value="Homeowner">Homeowner</option>
            <option value="Manager">Manager</option>
        </select>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Create Account</button>
        <a href="userAccountsMenu.php"><button type="button">Back</button></a>
    </form>
</div>

</body>
</html>
