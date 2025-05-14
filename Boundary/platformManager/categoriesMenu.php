<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Categories Menu - Platform Manager</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .dashboard-box {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            color: #FFD700;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }
        
        .dashboard-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .option-link {
            text-decoration: none;
        }
        
        .option {
            background-color: #252525;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 25px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .option:hover {
            transform: translateY(-5px);
            border-color: #FFD700;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
        }
        
        .option h2 {
            color: #FFD700;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .option p {
            color: #e0e0e0;
            text-align: center;
            margin: 0;
        }
        
        .option:before {
            content: '';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 48px;
            color: #FFD700;
            margin-bottom: 20px;
        }
        
        .option:nth-child(1):before { content: '\f02b'; } /* Create Tag */
        .option:nth-child(2):before { content: '\f03a'; } /* View List */
        .option:nth-child(3):before { content: '\f044'; } /* Edit */
        .option:nth-child(4):before { content: '\f002'; } /* Search */
        .option:nth-child(5):before { content: '\f2ed'; } /* Delete */
    </style>
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/manager-header.php'; ?>

<!-- Main content -->
<div class="dashboard-container">
    <div class="dashboard-box">
        <h1 class="dashboard-title">Service Category Management</h1>
        <p style="text-align: center; margin-bottom: 30px; color: #e0e0e0;">Select an action to perform:</p>

        <div class="dashboard-options">
            <a href="createServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Create Category</h2>
                    <p>Add a new service category to the system.</p>
                </div>
            </a>
            
            <a href="viewServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>View Categories</h2>
                    <p>See all available service categories.</p>
                </div>
            </a>
            
            <a href="editServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Edit Categories</h2>
                    <p>Update existing service categories.</p>
                </div>
            </a>
            
            <a href="searchServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Search Categories</h2>
                    <p>Find specific service categories.</p>
                </div>
            </a>
            
            <a href="deleteServiceCategory.php" class="option-link">
                <div class="option">
                    <h2>Delete Category</h2>
                    <p>Remove obsolete service categories.</p>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>