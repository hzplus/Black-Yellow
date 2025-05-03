<?php
class User {
    public $userid;
    public $username;
    public $email;
    public $password;
    public $role;

    public function __construct($userid, $username, $email, $password, $role) {
        $this->userid = $userid;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
}
?>
