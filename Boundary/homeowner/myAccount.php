<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

// Include controller
require_once '../../Controller/HomeownerController.php';
$homeownerController = new HomeownerController();

// Get homeowner information
$homeowner = $homeownerController->getHomeownerInfo($_SESSION['userid']);

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update profile info
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        
        $result = $homeownerController->updateHomeownerInfo($_SESSION['userid'], $name, $email, $phone, $address);
        if ($result) {
            $message = 'Profile information updated successfully.';
            // Refresh homeowner data
            $homeowner = $homeownerController->getHomeownerInfo($_SESSION['userid']);
        } else {
            $message = 'Failed to update profile information.';
        }
    }
    
    // Update password
    if (isset($_POST['update_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if ($newPassword !== $confirmPassword) {
            $message = 'New passwords do not match.';
        } else {
            $result = $homeownerController->updatePassword($_SESSION['userid'], $currentPassword, $newPassword);
            if ($result === true) {
                $message = 'Password updated successfully.';
            } else {
                $message = $result; // Error message from controller
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Account - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <img src="../../assets/images/logo.jpg" alt="Logo">
        </div>
        <div class="search-container">
            <form action="searchResults.php" method="GET">
                <input type="text" name="query" placeholder="Search for cleaners...">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="user-info">
            <span>Welcome Homeowner: <?php echo $_SESSION['username']; ?>!</span>
            <a href="../../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="homeownerDashboard.php">Home</a>
        <a href="searchCleaners.php">Find A Cleaner</a>
        <a href="shortlist.php">Shortlisted Cleaners</a>
        <a href="serviceHistory.php">Service History</a>
        <a href="myAccount.php" class="active">My Account</a>
    </div>
    
    <!-- My Account -->
    <div class="content-container">
        <h2>My Account</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="account-container">
            <div class="profile-section">
                <div class="profile-picture">
                    <img src="../../assets/images/profile-placeholder.png" alt="Profile Picture">
                </div>
                
                <div class="profile-info">
                    <form action="myAccount.php" method="POST">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $homeowner['name']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo $homeowner['email']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $homeowner['phone']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea id="address" name="address" rows="3"><?php echo $homeowner['address']; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="password-section">
                <h3>Change Password</h3>
                <form action="myAccount.php" method="POST">
                    <input type="hidden" name="update_password" value="1">
                    
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="save-btn">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>