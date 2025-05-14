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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Black&Yellow Cleaning Services</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="login-box">
                <div class="logo-container">
                    <img src="assets/images/logo.jpg" alt="Black&Yellow Logo">
                    <h1 class="login-title">Black&Yellow</h1>
                    <p class="login-subtitle">Cleaning Service Platform</p>
                </div>
                
                <?php if (!empty($message)): ?>
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="role-selector">
                        <i class="fas fa-user-tag icon"></i>
                        <select name="role" id="role" required>
                            <option value="">Select Your Role</option>
                            <option value="Admin">Administrator</option>
                            <option value="Cleaner">Service Cleaner</option>
                            <option value="Homeowner">Homeowner</option>
                            <option value="Manager">Platform Manager</option>
                        </select>
                    </div>
                    
                    <div class="login-field">
                        <label for="username">Username</label>
                        <i class="fas fa-user icon"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="login-field">
                        <label for="password">Password</label>
                        <i class="fas fa-lock icon"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="login-actions">
                        <button type="submit" class="login-btn">
                            Login <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </div>
                    
                    <div class="register-link">
                        Don't have an account? <a href="register.php">Register</a>
                    </div>
                </form>
            </div>
            
            <div class="login-footer">
                <p class="text-center text-muted">&copy; 2025 Black&Yellow Cleaning Services</p>
            </div>
        </div>
    </div>

    <script>
        // Simple animation for form fields
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.login-field input, .role-selector select').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentNode.classList.add('active');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentNode.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>