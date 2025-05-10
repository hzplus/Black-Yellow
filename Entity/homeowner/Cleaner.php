<?php
class Cleaner {
    private $id;
    private $name;
    private $email;
    private $status;
    private $bio;
    private $profilePicture;
    private $services = [];

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
        return $this;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    public function getServices() {
        return $this->services;
    }

    public function setServices($services) {
        $this->services = $services;
        return $this;
    }

    public function addService($service) {
        $this->services[] = $service;
        return $this;
    }
}
?>