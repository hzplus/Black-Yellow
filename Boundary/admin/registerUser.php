<?php
require_once '../../controller/admin/UserController.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $controller = new UserController();
    $success = $controller->createUser($username, $email, $password, $role);

    $message = $success ? "✅ Registered successfully!" : "❌ Registration failed.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BlacknYellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="topbar">
        <a href="login.php" style="color: #FFD700; text-decoration: none;">Login</a>
    </div>

    <div class="logo">
        <img src="../../assets/images/logo.jpg" alt="BlacknYellow Logo">
    </div>

    <div class="register-box">
        <h2>Register</h2>

        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

        <form method="POST">
            <label>User Type:</label>
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

            <button type="submit">Register</button>
            <a href="login.php"><button type="button">Back</button></a>
        </form>
    </div>
</body>
</html>
