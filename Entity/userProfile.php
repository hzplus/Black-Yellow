<?php

class userProfile {
    public $profileId;
    public $role;
    public $description;
    public $status;

    public function __construct($profileId, $role, $description, $status = 'active') {
        $this->profileId = $profileId;
        $this->role = $role;
        $this->description = $description;
        $this->status = $status;
    }

    public static function create($conn, $role, $description) {
        $stmt = $conn->prepare("INSERT INTO user_profiles (role, description) VALUES (?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("ss", $role, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function getAllProfiles($conn) {
        $stmt = $conn->prepare("SELECT * FROM user_profiles");
        $stmt->execute();
        $result = $stmt->get_result();

        $profiles = [];
        while ($row = $result->fetch_assoc()) {
            $profiles[] = new userProfile($row['profile_id'], $row['role'], $row['description'], $row['status'] ?? 'active');
        }

        $stmt->close();
        return $profiles;
    }

    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE profile_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new userProfile($row['profile_id'], $row['role'], $row['description'], $row['status'] ?? 'active');
        }

        return null;
    }

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
            $profiles[] = new userProfile($row['profile_id'], $row['role'], $row['description'], $row['status'] ?? 'active');
        }

        $stmt->close();
        return $profiles;
    }

    public static function update($conn, $id, $role, $description) {
        $stmt = $conn->prepare("UPDATE user_profiles SET role = ?, description = ? WHERE profile_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ssi", $role, $description, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public static function getActiveProfiles($conn) {
        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE status = 'active'");
        $stmt->execute();
        $result = $stmt->get_result();

        $profiles = [];
        while ($row = $result->fetch_assoc()) {
            $profiles[] = new userProfile($row['profile_id'], $row['role'], $row['description'], $row['status']);
        }

        $stmt->close();
        return $profiles;
    }

    public static function suspendProfiles($conn, $profileIds) {
        if (empty($profileIds)) return false;

        $placeholders = implode(',', array_fill(0, count($profileIds), '?'));
        $types = str_repeat('i', count($profileIds));

        $stmt = $conn->prepare("UPDATE user_profiles SET status = 'suspended' WHERE profile_id IN ($placeholders)");
        if (!$stmt) return false;

        $stmt->bind_param($types, ...$profileIds);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}