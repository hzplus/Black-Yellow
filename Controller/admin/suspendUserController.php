<?php
require_once '../../db/database.php';
require_once '../../Entity/user/user.php';

class suspendUserController {
    public function getActiveUsers() {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT userid, username, email, role FROM users WHERE status = 'active'");
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = new User($row['userid'], $row['username'], $row['email'], null, $row['role']);
        }

        $stmt->close();
        return $users;
    }

    public function suspend($userIds) {
        $conn = Database::connect();
        return User::suspendUsers($conn, $userIds);
    }
}
?>
