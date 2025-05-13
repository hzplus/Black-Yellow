<?php
// Controller/admin/createUserController.php

require_once DIR . '/../../Entity/user.php';

class createUserController {
    public function createUser(string $username, string $email, string $password, string $role): bool {
        return user::create($username, $email, $password, $role);
    }
}