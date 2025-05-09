<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/editServiceController.php');

if (!isset($_GET['serviceid'])) {
    echo "No service selected.";
    exit();
}

$serviceid = $_GET['serviceid'];
$service = EditServiceController::getServiceById($serviceid);

if (!$service) {
    echo "Service not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $category = $_POST['category'];
    $image_path = $_POST['image_path'] ?? null;

    $success = EditServiceController::updateService($serviceid, $title, $description, $price, $availability, $category, $image_path);

    if ($success) {
        header("Location: serviceListings.php");
        exit();
    } else {
        echo "Failed to update service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="topbar">
    <img src="../../assets/images/logo.jpg" alt="Logo">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
</div>

<div class="navbar">
    <a href="cleanerDashboard.php">Home</a>
    <a href="serviceListings.php">Service Listings</a>
    <a href="searchService.php">Search</a>
    <a href="../../logout.php">Logout</a>
</div>

<div class="dashboard">
    <h2>Edit Service</h2>

    <form method="POST" action="">
        <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($service['title']) ?>" required></label><br>
        <label>Description: <textarea name="description" required><?= htmlspecialchars($service['description']) ?></textarea></label><br>
        <label>Price: <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($service['price']) ?>" required></label><br>

        <label>Availability:</label><br>
        <select name="availability" required>
            <?php
            $options = ['Mon-Fri 9AM-12PM','Mon-Fri 12PM-3PM','Mon-Fri 3PM-6PM','Weekend 9AM-12PM','Weekend 12PM-3PM','Weekend 3PM-6PM','Flexible'];
            foreach ($options as $opt) {
                $selected = ($opt == $service['availability']) ? 'selected' : '';
                echo "<option value='$opt' $selected>$opt</option>";
            }
            ?>
        </select><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <?php
            $categories = ['All-in-one','Floor','Laundry','Toilet','Window'];
            foreach ($categories as $cat) {
                $selected = ($cat == $service['category']) ? 'selected' : '';
                echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
        </select><br><br>

        <label>Image Path: <input type="text" name="image_path" value="<?= htmlspecialchars($service['image_path']) ?>"></label><br>
        <button type="submit">Update Service</button>
    </form>
</div>

</body>
</html>
