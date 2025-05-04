<?php
require_once '../../db/Database.php';
require_once '../../Entity/user/user.php';

class viewUserController {
    public function getUserById($id) {
        $conn = Database::connect();
        return User::getById($conn, $id);
    }

    public function getAllUsers() {
        $conn = Database::connect();
        return User::getAllUsers($conn);
    }
}