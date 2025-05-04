<?php
require_once '../../db/Database.php';
require_once '../../Entity/user/user.php';

class editUserController {
    public function getUserById($id) {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateUser($id, $username, $email, $role, $status) {
        $conn = Database::connect();
        return User::update($conn, $id, $username, $email, $role, $status);
    }
}