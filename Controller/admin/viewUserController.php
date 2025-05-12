<?php
// Controller/admin/viewUserController.php

require_once __DIR__ . '/../../Entity/user.php';

class viewUserController
{
    /**
     * Fetch a single User by ID, or null.
     *
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        return User::getById($id);
    }

    /**
     * Fetch all users as an array of User objects.
     *
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return User::getAll();
    }
}