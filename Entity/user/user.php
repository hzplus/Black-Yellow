<?php

class User {
    public $userid;
    public $username;
    public $email;
    public $password;
    public $role;

    public function __construct($userid, $username, $email, $password, $role) {
        $this->userid   = $userid;
        $this->username = $username;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }

    // Used by CreateUserController (if you want to shift creation logic to entity)
    public static function create($conn, $username, $email, $password, $role) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if (!$stmt) return false;

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $email, $hashed, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Used by ViewUsersController
    public static function getAllUsers($conn) {
        $users = [];
    
        $stmt = $conn->prepare("SELECT userid, username, email, role, status FROM users ORDER BY userid ASC");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = new User(
                    $row['userid'],
                    $row['username'],
                    $row['email'],
                    null, // don’t load password
                    $row['role']
                );
                $users[count($users) - 1]->status = $row['status']; // add status manually
            }
        }
    
        $stmt->close();
        return $users;
    }
    public static function search($conn, $keyword) {
        $keyword = "%$keyword%";
        $stmt = $conn->prepare("SELECT userid, username, email, role, status FROM users WHERE username LIKE ? OR email LIKE ?");
    
        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }
    
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $users = [];
    
        while ($row = $result->fetch_assoc()) {
            $user = new User($row['userid'], $row['username'], $row['email'], null, $row['role']);
            $user->status = $row['status']; // manually assign status
            $users[] = $user;
        }
    
        $stmt->close();
        return $users;
    }

    public static function suspendUsers($conn, $userIds) {
        if (empty($userIds)) return false;
    
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $types = str_repeat('i', count($userIds));
        
        $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE userid IN ($placeholders)");
        if (!$stmt) return false;
    
        $stmt->bind_param($types, ...$userIds);
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public static function update($conn, $id, $username, $email, $role, $status) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, status = ? WHERE userid = ?");
        if (!$stmt) return false;
    
        $stmt->bind_param("ssssi", $username, $email, $role, $status, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function exists($conn, $username, $email) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    

    // Future: add getUserById, updateUser, deleteUser, etc.
}

