<?php
require_once __DIR__ . '/../../Entity/user.php';

class editUserController {

    // Get user details by ID
    public function getUserById($userId) {
        return user::getUserById($userId);
    }

    // Update user details
    public function updateUser($userId, $username, $email, $role, $status) {
        return user::updateUser($userId, $username, $email, $role, $status);
    }
}