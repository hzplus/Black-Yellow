<?php
require_once '../../Entity/service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Cleaner') {
        header("Location: ../../login.php");
        exit();
    }

    $cleanerid = $_SESSION['userid'];
    $keyword = $_POST['keyword'];

    $results = searchServicesByTitle($cleanerid, $keyword);

    // Store in session so the boundary can access it
    $_SESSION['search_results'] = $results;
    $_SESSION['search_keyword'] = $keyword;

    header("Location: ../../Boundary/cleaner/searchService.php");
    exit();
} else {
    header("Location: ../../Boundary/cleaner/searchService.php");
    exit();
}
