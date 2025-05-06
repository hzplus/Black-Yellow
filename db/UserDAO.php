<?php
require_once 'db.php';
require_once __DIR__ . '/../Entity/User.php';

class UserDAO {
    private $conn;
    
    public function __construct() {
        $db = new DB();
        $this->conn = $db->getConnection();
    }
    
    // Get user by ID
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userid = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User();
            $user->userid = $row['userid'];
            $user->username = $row['username'];
            $user->role = $row['role'];
            $user->email = $row['email'];
            $user->phone = $row['phone'];
            $user->address = $row['address'];
            
            return $user;
        }
        return null;
    }
    
    // Update user profile information
    public function updateUser($user) {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE userid = ?");
        $stmt->bind_param("ssssi", $user->username, $user->email, $user->phone, $user->address, $user->userid);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Update user password
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE userid = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Get homeowner specific information
    public function getHomeownerDetails($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM homeowners WHERE userid = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
?>