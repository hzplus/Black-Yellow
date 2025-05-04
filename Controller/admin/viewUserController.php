<?php
require_once '../../db/Database.php';
require_once '../../Entity/user/User.php';

class viewUserController {
    public function getAllUsers() {
        $conn = Database::connect();
        $users = User::getAllUsers($conn);
        Database::disconnect();
        return $users;
    }
}
?>
