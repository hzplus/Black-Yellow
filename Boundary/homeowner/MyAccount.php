<?php
// Boundary/homeowner/MyAccount.php
session_start();
require_once __DIR__ . '/../../Controller/homeowner/MyAccountController.php';

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Create controller instance
$controller = new MyAccountController();

// Get homeowner ID from session
$homeownerId = $_SESSION['userid'];

// Get homeowner data
$homeowner = $controller->getHomeownerById($homeownerId);

if (!$homeowner) {
    // Handle case where homeowner data can't be retrieved
    echo "Error loading account information.";
    exit();
}

// Handle form submissions
$profileMessage = '';
$passwordMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile update form
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        $result = $controller->updateHomeowner($homeownerId, $name, $email);
        
        if ($result === true) {
            $profileMessage = '<div class="alert success">Profile updated successfully!</div>';
            // Refresh homeowner data
            $homeowner = $controller->getHomeownerById($homeownerId);
        } else {
            $profileMessage = '<div class="alert error">' . $result . '</div>';
        }
    }
    
    // Password change form
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $result = $controller->updatePassword(
            $homeownerId, 
            $currentPassword, 
            $newPassword, 
            $confirmPassword
        );
        
        if ($result === true) {
            $passwordMessage = '<div class="alert success">Password changed successfully!</div>';
        } else {
            $passwordMessage = '<div class="alert error">' . $result . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Black&Yellow Cleaning</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">My Account</h1>
    
    <div class="account-container">
        <!-- Profile Information Section -->
        <div class="account-section">
            <h2>Profile Information</h2>
            
            <?= $profileMessage ?>
            
            <form method="POST" class="account-form">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($homeowner['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($homeowner['email']); ?>" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="primary-btn">Save Changes</button>
                </div>
            </form>
        </div>
        
        <!-- Change Password Section -->
        <div class="account-section">
            <h2>Change Password</h2>
            
            <?= $passwordMessage ?>
            
            <form method="POST" class="account-form">
                <input type="hidden" name="change_password" value="1">
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <div class="field-hint">Password must be at least 6 characters</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="primary-btn">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
        if (confirmPasswordInput.value && confirmPasswordInput.value !== newPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }
    
    newPasswordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
});
</script>

</body>
</html>