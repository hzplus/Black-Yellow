<?php
require_once '../../db/Database.php';
require_once '../../Entity/user/user.php';

class createUserController {
    public function userExists($username, $email) {
        $conn = Database::getConnection();

        return User::exists($conn, $username, $email);
    }

    public function createUser($username, $email, $password, $role) {
        $conn = Database::getConnection();

        return User::create($conn, $username, $email, $password, $role);
    }
}
