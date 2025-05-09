<?php
// Entity/userProfile.php

require_once __DIR__ . '/../db/Database.php';

class userProfile
{
    public int    $profile_id;
    public string $role;
    public string $description;
    public string $status;

    public function __construct(int $profile_id, string $role, string $description, string $status = 'active')
    {
        $this->profile_id  = $profile_id;
        $this->role        = $role;
        $this->description = $description;
        $this->status      = $status;
    }

    /** Create a new profile */
    public static function create(string $role, string $description): bool
    {
        $conn = Database::connect();
        $sql  = "INSERT INTO user_profiles (role, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return false;
        }

        $stmt->bind_param("ss", $role, $description);
        $ok = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $ok;
    }

    /** Check if a role already exists */
    public static function exists(string $role): bool
    {
        $conn = Database::connect();
        $sql  = "SELECT role FROM user_profiles WHERE role = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return false;
        }

        $stmt->bind_param("s", $role);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();
        $conn->close();
        return $exists;
    }

    /** Fetch all profiles */
    public static function getAll(): array
    {
        $conn     = Database::connect();
        $profiles = [];

        $sql  = "SELECT profile_id, role, description, status FROM user_profiles ORDER BY profile_id";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $profiles[] = new userProfile(
                    (int)$row['profile_id'],
                    $row['role'],
                    $row['description'],
                    $row['status']
                );
            }
            $stmt->close();
        }

        $conn->close();
        return $profiles;
    }

    /** Fetch one profile by ID */
    public static function getById(int $id): ?userProfile
    {
        $conn = Database::connect();
        $sql  = "SELECT profile_id, role, description, status FROM user_profiles WHERE profile_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $profile = new userProfile(
                (int)$row['profile_id'],
                $row['role'],
                $row['description'],
                $row['status']
            );
            $stmt->close();
            $conn->close();
            return $profile;
        }

        $stmt->close();
        $conn->close();
        return null;
    }

    /** Update a profile */
    public static function update(int $id, string $role, string $description, string $status): bool
    {
        $conn = Database::connect();
        $sql  = "UPDATE user_profiles SET role = ?, description = ?, status = ? WHERE profile_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return false;
        }

        $stmt->bind_param("sssi", $role, $description, $status, $id);
        $ok = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $ok;
    }

    /** Delete a profile */
    public static function delete(int $id): bool
    {
        $conn = Database::connect();
        $sql  = "DELETE FROM user_profiles WHERE profile_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return false;
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $ok;
    }

    public static function searchByRole(string $keyword): array {
        $conn = Database::connect();
        $profiles = [];
    
        $sql = "SELECT profile_id, role, description, status 
                FROM user_profiles 
                WHERE role LIKE ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return [];
        }
    
        $likeKeyword = "%$keyword%";
        $stmt->bind_param("s", $likeKeyword);
        $stmt->execute();
        $res = $stmt->get_result();
    
        while ($row = $res->fetch_assoc()) {
            $profiles[] = new userProfile(
                (int)$row['profile_id'],
                $row['role'],
                $row['description'],
                $row['status']
            );
        }
    
        $stmt->close();
        $conn->close();
        return $profiles;
    }

    public static function suspend(int $id): bool {
        $conn = Database::connect();
        $sql = "UPDATE user_profiles SET status = 'suspended' WHERE profile_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            return false;
        }
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $ok;
    }
}
