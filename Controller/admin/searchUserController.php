<?php


require_once __DIR__ . '/../../entity/user.php';

class searchUserController
{
    /**
     * Return an array of User objects matching $keyword,
     * or all users if $keyword is empty.
     *
     * @param string $keyword
     * @return User[]
     */
    public function search(string $keyword): array
    {
        // Delegate entirely to the Entity
        return User::search($keyword);
    }
}