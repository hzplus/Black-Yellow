<?php

class userProfile {
    public $profileId;
    public $role;
    public $description;

    public function __construct($profileId, $role, $description) {
        $this->profileId = $profileId;
        $this->role = $role;
        $this->description = $description;
    }

    // Create a new user profile
    public static function create($conn, $role, $description) {
        $stmt = $conn->prepare("INSERT INTO user_profiles (role, description) VALUES (?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ss", $role, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Get all user profiles
    public static function getAllProfiles($conn) {
        $stmt = $conn->prepare("SELECT * FROM user_profiles");
        $stmt->execute();
        $result = $stmt->get_result();

        $profiles = [];
        while ($row = $result->fetch_assoc()) {
            $profiles[] = new userProfile($row['profile_id'], $row['role'], $row['description']);
        }

        $stmt->close();
        return $profiles;
    }

    // Get a single user profile by ID
    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE profile_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new userProfile($row['profile_id'], $row['role'], $row['description']);
        }

        return null;
    }


    // Search user profiles by role or description
    public static function search($conn, $keyword) {
        $keyword = "%$keyword%";
        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE role LIKE ? OR description LIKE ?");
        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }

        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $profiles = [];
        while ($row = $result->fetch_assoc()) {
            $profiles[] = new userProfile($row['profile_id'], $row['role'], $row['description']);
        }

        $stmt->close();
        return $profiles;
    }
}