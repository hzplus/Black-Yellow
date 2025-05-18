<?php
// Controller/auth/authController.php

require_once __DIR__ . '/../../Entity/User.php';

class authController {
    public function getUser($username, $role) {
        return User::findByUsernameAndRole($username, $role);
    }
    
    public function getRoleStatus($role) {
    return User::getRoleStatus($role);
}
}
