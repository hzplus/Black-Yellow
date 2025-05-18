<?php
// Controller/auth/authController.php

require_once __DIR__ . '/../../Entity/User.php';

class authController {
    public function login($username, $password, $role) {
        return User::login($username, $password, $role);
    }
}
