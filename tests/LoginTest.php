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
        // Arrange
        $password = "password1234";
        
        // Act
        $this->user->SetPassword($password);
        $result = $this->user->GetPassword();
        
        // Assert
        $this->assertEquals($password, $result, "Failed to set and get password correctly.");
    }

    public function testValidateUserWithEmptyPassword()
    {
        // Arrange
        $this->user->username = "test";
        
        // Act
        $errors = $this->user->ValidateUser();
        
        // Assert
        $this->assertContains("Password cannot be empty", $errors, "Failed to validate user with empty password.");
    }

    public function testValidateUserWithShortName()
    {
        // Arrange
        $this->user->username = "test";
        
        // Act
        $errors = $this->user->ValidateUser();
        
        // Assert
        $this->assertContains("Username must be between 3 and 50 characters", $errors, "Failed to validate user with short name.");
    }

    public function testIsLoggedinWhenNotLoggedIn()
    {
        // Act
        $isLoggedIn = $this->user->IsLoggedin();
        
        // Assert
        $this->assertFalse($isLoggedIn, "User is considered logged in when not logged in.");
    }

    public function testIsLoggedinWhenLoggedIn()
    {
        // Arrange
        session_start();
        $_SESSION['username'] = "test";
        
        // Act
        $isLoggedIn = $this->user->IsLoggedin();
        
        // Assert
        $this->assertTrue($isLoggedIn, "User is considered not logged in when logged in.");
    }

    public function testLogout()
    {
        // Arrange
        session_start();
        
        // Act
        $this->user->logout();
        
        // Assert
        $isDeleted = (session_status() == PHP_SESSION_NONE || empty(session_id()));
        $this->assertTrue($isDeleted, "Failed to logout user.");
    }
}

?>
