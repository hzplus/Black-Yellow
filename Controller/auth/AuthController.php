<?php
require_once __DIR__ . '/../../db/Database.php';

class authController {
    public function login($username, $password, $role) {
        $conn = Database::connect();

        // Fetch the user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // 1. Verify password
            if (password_verify($password, $user['password'])) {

                // 2. Check if user account is suspended
                if ($user['status'] === 'suspended') {
                    return "❌ Your account has been suspended.";
                }

                // 3. Check if profile (role) is suspended
                $stmt2 = $conn->prepare("SELECT status FROM user_profiles WHERE role = ?");
                $stmt2->bind_param("s", $role);
                $stmt2->execute();
                $profileResult = $stmt2->get_result();

                if ($profile = $profileResult->fetch_assoc()) {
                    if ($profile['status'] === 'suspended') {
                        return "❌ The user profile \"$role\" has been suspended.";
                    }
                }

                // 4. Return user as object
                return (object)[
                    'userid'   => $user['userid'],
                    'username' => $user['username'],
                    'role'     => $user['role']
                ];
            }
        }

        return false;
    }
}
