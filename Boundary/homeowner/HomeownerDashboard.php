<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homeowner Dashboard - Black&Yellow</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/homeowner-header.php'; ?>

<div class="dashboard-content container">
    <h1 class="dashboard-title">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Homeowner'; ?></h1>
    <p class="dashboard-subtitle">Manage your cleaning services</p>
    
    <!-- Stats Cards -->
    <div class="d-flex gap-4 flex-wrap mb-5">
        <div class="card" style="flex: 1; min-width: 200px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check text-primary" style="font-size: 2.5rem; margin-right: var(--spacing-md);"></i>
                <div>
                    <div class="fs-xl fw-bold text-primary">5</div>
                    <div class="text-muted">Upcoming Bookings</div>
                </div>
            </div>
        </div>
        
        <div class="card" style="flex: 1; min-width: 200px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-bookmark text-primary" style="font-size: 2.5rem; margin-right: var(--spacing-md);"></i>
                <div>
                    <div class="fs-xl fw-bold text-primary">3</div>
                    <div class="text-muted">Shortlisted Cleaners</div>
                </div>
            </div>
        </div>
        
        <div class="card" style="flex: 1; min-width: 200px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-primary" style="font-size: 2.5rem; margin-right: var(--spacing-md);"></i>
                <div>
                    <div class="fs-xl fw-bold text-primary">12</div>
                    <div class="text-muted">Completed Services</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Services -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="card-title">Recent Services</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Service</th>
                            <th>Date & Type</th>
                            <th>Cleaner</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="tag">Completed</span></td>
                            <td>Deep Cleaning - Kitchen</td>
                            <td>May 10, 2025<br><span class="text-muted fs-sm">Kitchen Cleaning</span></td>
                            <td>John Smith</td>
                            <td class="text-primary fw-bold">$80.00</td>
                            <td><a href="ServiceDetails.php?id=1" class="btn btn-sm">View Details</a></td>
                        </tr>
                        <tr>
                            <td><span class="tag">Completed</span></td>
                            <td>Window Cleaning</td>
                            <td>May 5, 2025<br><span class="text-muted fs-sm">Window Cleaning</span></td>
                            <td>Jane Doe</td>
                            <td class="text-primary fw-bold">$45.00</td>
                            <td><a href="ServiceDetails.php?id=2" class="btn btn-sm">View Details</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="ServiceHistory.php" class="btn btn-outline">View All Services</a>
            </div>
        </div>
    </div>
    
    <!-- Dashboard Options -->
    <h2 class="mb-3">Quick Actions</h2>
    <div class="dashboard-options">
        <a href="BrowseCleaners.php" class="option-card">
            <div class="icon"><i class="fas fa-search"></i></div>
            <h3>Find a Cleaner</h3>
            <p>Search for cleaners available in your area.</p>
        </a>
        
        <a href="ShortlistedCleaners.php" class="option-card">
            <div class="icon"><i class="fas fa-bookmark"></i></div>
            <h3>Shortlisted Cleaners</h3>
            <p>View your saved and shortlisted cleaners.</p>
        </a>
        
        <a href="ServiceHistory.php" class="option-card">
            <div class="icon"><i class="fas fa-history"></i></div>
            <h3>Service History</h3>
            <p>Review past service bookings and feedback.</p>
        </a>
        
        <a href="MyAccount.php" class="option-card">
            <div class="icon"><i class="fas fa-user-cog"></i></div>
            <h3>My Account</h3>
            <p>View and update your account information.</p>
        </a>
    </div>
</div>

<script>
    // You can add JavaScript here if needed
</script>
</body>
</html>