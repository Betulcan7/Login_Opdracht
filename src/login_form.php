<?php

require_once "../vendor/autoload.php";
use LoginOpdracht\classes\User;

// Inschakelen van foutmeldingen voor debuggen
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['login-btn']) ){
    //require_once('classes/user.php');
   

    $user = new User();

    $user->username = $_POST['username'];
    $user->SetPassword($_POST['password']);

    $errors = $user->ValidateUser();

    if(count($errors) == 0){
        if ($user->LoginUser()){
            // Sessievariabelen instellen na succesvol inloggen
            session_start();
            $_SESSION['username'] = $user->username;
            header("location: index.php");
            exit();
        } else {
            array_push($errors, "Login mislukt");
        }
    }

    if(count($errors) > 0){
        $message = implode("\\n", $errors);
        
        echo "<script>alert('" . $message . "')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h3>PHP - PDO Login and Registration</h3>
    <hr/>
    
    <form action="" method="POST">    
        <h4>Login here...</h4>
        <hr>
        
        <label>Username</label>
        <input type="text" name="username" />
        <br>
        <label>Password</label>
        <input type="password" name="password" />
        <br>
        <button type="submit" name="login-btn">Login</button>
        <br>
        <a href="register_form.php">Registration</a>
    </form>
</body>
</html>
