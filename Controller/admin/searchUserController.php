<?php
require_once __DIR__ . '/../../db/database.php';
require_once __DIR__ . '/../../entity/user/user.php';

class searchUserController {
    public function search($keyword) {
        $conn = database::connect();
        $users = user::search($conn, $keyword);
        database::disconnect();
        return $users;
    }
}
?>