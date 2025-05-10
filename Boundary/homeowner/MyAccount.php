<?php
require_once 'Controllers/AccountController.php';

$homeownerId = 1; // Example, fetch dynamically based on session
$controller = new AccountController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'password' => $_POST['password']
    ];
    $controller->updateProfile($homeownerId, $newData);
}

echo '<h1>My Account</h1>';

echo '<form method="POST">';
echo '<label for="name">Name</label><input type="text" name="name" value="Current Name">';
echo '<label for="email">Email</label><input type="email" name="email" value="Current Email">';
echo '<label for="phone">Phone</label><input type="text" name="phone" value="Current Phone">';
echo '<label for="address">Address</label><input type="text" name="address" value="Current Address">';
echo '<label for="password">Password</label><input type="password" name="password" value="Current Password">';
echo '<button type="submit">Update Profile</button>';
echo '</form>';
?>