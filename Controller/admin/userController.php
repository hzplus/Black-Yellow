<?php
require_once '../../db/Database.php';
require_once '../../Entity/user/User.php';

class UserController {
    public function createUser($username, $email, $password, $role) {
        $conn = Database::connect();

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("SQL prepare error: " . $conn->error);
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);

        $result = $stmt->execute();
        $stmt->close();
        Database::disconnect();

        return $result;
    }
}
?>
