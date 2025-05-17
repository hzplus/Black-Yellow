<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/searchServiceController.php');

$cleanerId = $_SESSION['userid'];
$keyword = $_GET['keyword'] ?? '';
$services = [];

if (!empty($keyword)) {
    $services = SearchServiceController::searchServicesByTitle($cleanerId, $keyword);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Services</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<!-- Include the header (topbar and navbar) -->
<?php include '../../assets/includes/cleaner-header.php'; ?>

<div class="services-section">
    <h2>Search Your Services</h2>
    <form method="GET" action="searchService.php">
        <input type="text" name="keyword" placeholder="Search by title or category..." value="<?= htmlspecialchars($keyword) ?>" required>
        <button type="submit">Search</button>
        <a href="searchService.php" class="button" style="margin-left: 10px;">Clear</a>

    </form>

    <div class="service-cards">
        <?php if (!empty($services)) {
            foreach ($services as $service) { ?>
                <div class="service-card" onclick="location.href='viewService.php?serviceid=<?= $service['serviceid'] ?>'">
                    <?= htmlspecialchars($service['title']) ?>
                </div>
        <?php }} elseif (!empty($keyword)) { ?>
            <p>No results found for "<?= htmlspecialchars($keyword) ?>".</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
