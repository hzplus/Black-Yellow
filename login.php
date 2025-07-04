<?php
session_start();
require_once 'Controller/auth/authController.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $auth = new authController();
    $user = $auth->login($username, $password, $role);

    if (is_string($user)) {
        // Suspended message or custom error
        $message = $user;
    } elseif ($user) {
        $_SESSION['userid'] = $user->userid;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        switch ($user->role) {
            case 'Admin':
                header("Location: Boundary/admin/adminDashboard.php");
                break;
            case 'Cleaner':
                header("Location: Boundary/cleaner/cleanerDashboard.php");
                break;
            case 'Homeowner':
                header("Location: Boundary/homeowner/homeownerDashboard.php");
                break;
            case 'Manager':
                header("Location: Boundary/manager/managerDashboard.php");
                break;
        }
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - Black&Yellow</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <a href="login.php" style="color: #FFD700; text-decoration: none;">Login</a>
</div>

<!-- Logo -->
<div class="logo">
    <img src="assets/images/logo.jpg" alt="Logo">
</div>

<!-- Login Form -->
<div class="register-box">
    <h2>Login</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>

    <form method="POST">
        <label>User Type:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Cleaner">Cleaner</option>
            <option value="Homeowner">Homeowner</option>
            <option value="Manager">Manager</option>
        </select>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
        <a href="Boundary/admin/createUserAccountUI.php"><button type="button">Register</button></a>
    </form>
</div>

</body>
</html>
