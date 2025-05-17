<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Controller/auth/AuthController.php';

class AuthControllerTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        $this->auth = new AuthController();
    }

    // ✅ Admin
    // public function testAdminLoginCorrect()
    // {
    //     $user = $this->auth->login('admin3', 'admin3', 'Admin');
    //     $this->assertEquals('Admin', $user->role);
    // }

    // public function testAdminLoginWrongPassword()
    // {
    //     $result = $this->auth->login('admin3', 'wrongpass', 'Admin');
    //     $this->assertFalse($result);
    // }

    // // ✅ Cleaner
    // public function testCleanerLoginCorrect()
    // {
    //     $user = $this->auth->login('cleaner', 'cleaner', 'Cleaner');
    //     $this->assertEquals('Cleaner', $user->role);
    // }

    // public function testCleanerLoginWrongPassword()
    // {
    //     $result = $this->auth->login('cleaner', 'wrongpass', 'Cleaner');
    //     $this->assertFalse($result);
    // }

    // // ✅ Homeowner
    // public function testHomeownerLoginCorrect()
    // {
    //     $user = $this->auth->login('homeowner', 'homeowner', 'Homeowner');
    //     $this->assertEquals('Homeowner', $user->role);
    // }

    // public function testHomeownerLoginWrongPassword()
    // {
    //     $result = $this->auth->login('homeowner', 'wrongpass', 'Homeowner');
    //     $this->assertFalse($result);
    // }

    // // ✅ Platform Manager
    // public function testManagerLoginCorrect()
    // {
    //     $user = $this->auth->login('manager', 'manager', 'Manager');
    //     $this->assertEquals('Manager', $user->role);
    // }

    public function testManagerLoginWrongPassword()
    {
        $result = $this->auth->login('manager', 'wrongpass', 'Manager');
        $this->assertFalse($result);
    }
}
