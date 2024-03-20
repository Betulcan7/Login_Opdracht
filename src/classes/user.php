<?php
namespace LoginOpdracht\classes;

use PDO;
use PDOException;

class User{

    public $username;
    private $password;
    private $pdo;

    function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=login_oop", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    function SetPassword($password){
        $this->password = $password;
    }

    function ValidateUser(){
        $errors=[];

        if (empty($this->username)){
            array_push($errors, "Gebruikersnaam mag niet leeg zijn.");
        } elseif (strlen($this->username) < 3 || strlen($this->username) > 50) {
            array_push($errors, "Gebruikersnaam moet tussen de 3 en 50 tekens lang zijn.");
        }

        if (empty($this->password)){
            array_push($errors, "Wachtwoord mag niet leeg zijn.");
        }

        return $errors;
    }

    public function IsLoggedin() {
        return isset($_SESSION['username']);
    }

    public function Logout(){
        unset($_SESSION['username']);
        header('location: index.php');
        exit; 
    }
}
?>
