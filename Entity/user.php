<?php
// Entity/user.php

require_once __DIR__ . '/../db/Database.php';

class User
{
    public int    $userid;
    public string $username;
    public string $email;
    public string $role;
    public string $status;

    public function __construct(
        int    $userid,
        string $username,
        string $email,
        string $role,
        string $status = 'active'
    ) {
        $this->userid   = $userid;
        $this->username = $username;
        $this->email    = $email;
        $this->role     = $role;
        $this->status   = $status;
    }

    /**
     * Create a new user.
     */
    public static function create(string $username, string $email, string $password, string $role): bool {
        $conn = Database::getConnection();
    
        // Step 1: Check if user already exists
        $checkSql = "SELECT userid FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($checkSql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return false; // User exists
        }
        $stmt->close();
    
        // Step 2: Insert new user
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $insertSql = "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'active')";
        $stmt = $conn->prepare($insertSql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        $stmt->bind_param("ssss", $username, $email, $hash, $role);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Check if a username or email already exists.
     */
    public static function exists(string $username, string $email): bool
    {
        $conn = Database::getConnection();

        $sql  = "SELECT 1 FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Fetch a single user by ID.
     */
    public static function getById(int $userid): ?User
    {
        $conn = Database::getConnection();

        $sql  = "SELECT userid, username, email, role, status
                 FROM users
                 WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user = new User(
                (int)$row['userid'],
                $row['username'],
                $row['email'],
                $row['role'],
                $row['status']
            );
            $stmt->close();
            return $user;
        }

        $stmt->close();
        return null;
    }

    /**
     * Update an existing user.
     */
    public static function updateUser(
        int    $userid,
        string $username,
        string $email,
        string $role,
        string $status
    ): bool {
        $conn = Database::getConnection();

        $sql  = "UPDATE users
                 SET username = ?, email = ?, role = ?, status = ?
                 WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssssi", $username, $email, $role, $status, $userid);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Delete a user by ID.
     */
    public static function delete(int $userid): bool
    {
        $conn = Database::getConnection();

        $sql  = "DELETE FROM users WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $userid);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Fetch all users.
     *
     * @return User[]
     */
    public static function getAll(): array
    {
        $conn = Database::getConnection();
        $users = [];

        $sql  = "SELECT userid, username, email, role, status
                 FROM users
                 ORDER BY userid";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = new User(
                    (int)$row['userid'],
                    $row['username'],
                    $row['email'],
                    $row['role'],
                    $row['status']
                );
            }
            $stmt->close();
        }

        return $users;
    }

    public static function getUserById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object(); // return user object
    }

    /**
     * Search users by username or email.
     *
     * @return User[]
     */
    public static function search(string $term): array
    {
        $conn = Database::getConnection();
        $users = [];

        $sql  = "SELECT userid, username, email, role, status
                 FROM users
                 WHERE username LIKE ? OR email LIKE ?
                 ORDER BY userid";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $like = "%{$term}%";
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = new User(
                    (int)$row['userid'],
                    $row['username'],
                    $row['email'],
                    $row['role'],
                    $row['status']
                );
            }
            $stmt->close();
        }

        return $users;
    }

    public static function suspend(int $userid): bool
    {
        $conn = Database::getConnection();
        $sql  = "UPDATE users SET status = 'suspended' WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $userid);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

}
