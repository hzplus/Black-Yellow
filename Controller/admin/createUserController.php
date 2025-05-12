<?php
// Controller/admin/createUserController.php

require_once __DIR__ . '/../../Entity/user.php';

class createUserController
{
    public function userExists(string $username, string $email): bool
    {
        return User::exists($username, $email);
    }

    public function createUser(string $username, string $email, string $password, string $role): bool
    {
        return User::create($username, $email, $password, $role);
    }
}