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
                header("Location: Boundary/platformManager/managerDashboard.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Black&Yellow</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="assets/images/logo.jpg" alt="Black&Yellow Logo">
            </div>
            <h2>Black&Yellow Cleaning Services</h2>
            
            <?php if (!empty($message)): ?>
                <div class="error">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="role">User Type</label>
                    <select name="role" id="role" required>
                        <option value="Admin">Admin</option>
                        <option value="Cleaner">Cleaner</option>
                        <option value="Homeowner">Homeowner</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Login</button>
                    <a href="register.php"><button type="button">Register</button></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>