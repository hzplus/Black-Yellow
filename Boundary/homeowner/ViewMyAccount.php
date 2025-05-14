<?php
// Start output buffering to prevent any debug output
ob_start();

session_start();

// Redirect if not logged in
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}

// Include controller
require_once(__DIR__ . '/../../Controller/homeowner/AccountController.php');
$controller = new AccountController();

// Get homeowner ID
$homeownerId = $_SESSION['userid'];

// Get homeowner data
$homeowner = $controller->getHomeownerById($homeownerId);

// Handle form submission
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? ''; // This will be ignored in the backend
    $address = $_POST['address'] ?? ''; // This will be ignored in the backend
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Verify current password if changing password
    if (!empty($newPassword)) {
        if (empty($currentPassword)) {
            $updateMessage = '<div class="alert error">Please enter your current password to set a new password.</div>';
        } else if ($newPassword !== $confirmPassword) {
            $updateMessage = '<div class="alert error">New password and confirmation do not match.</div>';
        } else {
            // Update with password change
            $result = $controller->updateHomeownerWithPassword($homeownerId, $name, $email, $phone, $address, $currentPassword, $newPassword);
            
            if ($result === true) {
                $updateMessage = '<div class="alert success">Account information updated successfully!</div>';
                // Refresh homeowner data
                $homeowner = $controller->getHomeownerById($homeownerId);
            } else {
                $updateMessage = '<div class="alert error">' . $result . '</div>';
            }
        }
    } else {
        // Update without password change
        $result = $controller->updateHomeowner($homeownerId, $name, $email, $phone, $address);
        
        if ($result === true) {
            $updateMessage = '<div class="alert success">Account information updated successfully!</div>';
            // Refresh homeowner data
            $homeowner = $controller->getHomeownerById($homeownerId);
        } else {
            $updateMessage = '<div class="alert error">' . $result . '</div>';
        }
    }
}

// Clear the output buffer to remove any debug output
ob_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Cleaning Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .note {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .disabled-field {
            background-color: #f8f9fa;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="content-container">
    <a href="HomeownerDashboard.php" class="back-button">← Back to Dashboard</a>
    
    <h1 class="section-title">My Account</h1>
    
    <?php echo $updateMessage; ?>
    
    <div class="account-container">
        <form method="POST" class="account-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($homeowner->getName()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($homeowner->getEmail()); ?>" required>
            </div>
            
            <!-- These fields are displayed but not actually saved in the database -->
            <div class="divider">
                <h3>Change Password</h3>
                <p class="help-text">Leave blank to keep current password</p>
            </div>
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Password confirmation validation
    document.addEventListener('DOMContentLoaded', function() {
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const currentPasswordInput = document.getElementById('current_password');
        
        function checkPasswordMatch() {
            if (newPasswordInput.value && confirmPasswordInput.value) {
                if (confirmPasswordInput.value !== newPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Passwords do not match');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            }
            
            // If new password is entered, require current password
            if (newPasswordInput.value && !currentPasswordInput.value) {
                currentPasswordInput.setCustomValidity('Current password is required to set a new password');
            } else {
                currentPasswordInput.setCustomValidity('');
            }
        }
        
        newPasswordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        currentPasswordInput.addEventListener('input', checkPasswordMatch);
    });
</script>

</body>
</html>