<?php
require_once 'db/Database.php';
require_once 'Entity/user/user.php';

class authController {
    public function login($username, $password, $role) {
        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['status'] === 'suspended') {
                return "Your account has been suspended.";
            }

            if (password_verify($password, $row['password'])) {
                return new User($row['userid'], $row['username'], $row['email'], $row['password'], $row['role']);
            }
        }

        return null; // Invalid credentials
    }
}
?>
