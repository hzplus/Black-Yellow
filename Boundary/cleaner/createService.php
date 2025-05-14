<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/createServiceController.php');

$categoryOptions = CreateServiceController::fetchCategories();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cleanerId = $_SESSION['userid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $category = $_POST['category'];
    $image_path = null; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            echo "Image upload failed.";
            exit();
        }
        $image_path = 'assets/images/' . $imageName;
    }


    CreateServiceController::handleCreateRequest(
        $cleanerId,
        $title,
        $description,
        $price,
        $availability,
        $category,
        $image_path
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Service</title>
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>
<body>

    <!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/cleaner-header.php'; ?>

    <div class="dashboard">
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Title: <input type="text" name="title" required></label><br>
            <label>Description: <textarea name="description" required></textarea></label><br>
            <label>Price: <input type="number" step="0.01" name="price" required></label><br>

            <label>Availability:</label><br>
            <select name="availability" required>
                <option value="Mon-Fri 9AM-12PM">Mon-Fri 9AM-12PM</option>
                <option value="Mon-Fri 12PM-3PM">Mon-Fri 12PM-3PM</option>
                <option value="Mon-Fri 3PM-6PM">Mon-Fri 3PM-6PM</option>
                <option value="Weekend 9AM-12PM">Weekend 9AM-12PM</option>
                <option value="Weekend 12PM-3PM">Weekend 12PM-3PM</option>
                <option value="Weekend 3PM-6PM">Weekend 3PM-6PM</option>
                <option value="Flexible">Flexible</option>
            </select><br><br>
            
            <label>Category:</label><br>
            <select name="category" required>
                <?php foreach ($categoryOptions as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <!-- <label>Category:</label><br>
            <select name="category" required>
                <option value="All-in-one">All-in-one</option>
                <option value="Floor">Floor</option>
                <option value="Laundry">Laundry</option>
                <option value="Toilet">Toilet</option>
                <option value="Window">Window</option>
            </select><br><br> -->

            <label>Upload Image: <input type="file" name="image" accept="image/*"></><br>
            <button type="submit">Create Service</button>
        </form>
    </div>

</body>
</html>
