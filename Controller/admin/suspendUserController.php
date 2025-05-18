<?php
// Controller/admin/suspendUserController.php

require_once __DIR__ . '/../../Entity/user.php';

class suspendUserController
{
    /**
     * Fetch only users whose status is “active”
     *
     * @return User[] 
     */
    public function getActiveUsers(): array
    {
        // Pull all users from the Entity…
        $all = User::getAll();
        // …and return only those with status === 'active'
        return array_filter($all, fn(User $u) => $u->status === 'active');
    }

    /**
     * Suspend multiple users by setting their status to 'suspended'.
     *
     * @param int[] $userIds
     * @return bool  True if all succeeded
     */
    public function suspendUsers(array $userIds): bool
    {
        $ok = true;
        foreach ($userIds as $id) {
            if (!User::suspend((int)$id)) {
                $ok = false;
            }
        }
        return $ok;
    }
}
