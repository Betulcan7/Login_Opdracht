<?php

use PHPUnit\Framework\TestCase;
use LoginOpdracht\classes\User;

class LoginTest extends TestCase {
    
    protected $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testSetPasswordAndGetPassword()
    {
        
        $password = "test";
        
        $this->user->SetPassword($password);
        $result = $this->user->GetPassword();
        
        $this->assertEquals($password, $result, "Failed to set and get password correctly.");
    }

    public function testValidateUserWithEmptyPassword()
    {

        $this->user->username = "test";
        
        $errors = $this->user->ValidateUser();
        
        $this->assertContains("Password cannot be empty", $errors, "Failed to validate user with empty password.");
    }

    public function testRegisterUser()
    {
        $this->user->username = "test";
        $this->user->SetPassword("test");
    
        $errors = $this->user->RegisterUser();
    
        $this->assertEmpty($errors, "Failed to register user.");
    }
    

    public function testValidateUserWithShortName()
    {
      
        $this->user->username = "test";
        
        $errors = $this->user->ValidateUser();
        
        $this->assertContains("Username must be between 3 and 50 characters", $errors, "Failed to validate user with short name.");
    }

    public function testIsLoggedinWhenNotLoggedIn()
    {
        $isLoggedIn = $this->user->IsLoggedin();
        
        $this->assertFalse($isLoggedIn, "User is considered logged in when not logged in.");
    }

    public function testIsLoggedinWhenLoggedIn()
    {
        session_start();
        $_SESSION['username'] = "test";
        
        $isLoggedIn = $this->user->IsLoggedin();
        
        $this->assertTrue($isLoggedIn, "User is considered not logged in when logged in.");
    }

    public function testLogout()
    {
        session_start();
        
        $this->user->logout();
        
        $isDeleted = (session_status() == PHP_SESSION_NONE || empty(session_id()));
        $this->assertTrue($isDeleted, "Failed to logout user.");
    }
}

?>
