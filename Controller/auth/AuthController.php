<?php
require_once __DIR__ . '/../../db/Database.php';

class authController {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function login($username, $password, $role) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_object()) {
            // ✅ Use password_verify here
            if (password_verify($password, $user->password)) {
                return $user;
            } else {
                return false; // invalid password
            }
        } else {
            return false; // user not found
        }
    }
}
