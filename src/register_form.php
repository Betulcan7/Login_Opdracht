<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

<?php
 require_once "../vendor/autoload.php";
 use LoginOpdracht\classes\User;

// Is de register button aangeklikt?
if(isset($_POST['register-btn'])){

  //  require_once('classes/user.php');

    $user = new User();
    $errors=[];

    $user->username = $_POST['username'];
    $user->SetPassword($_POST['password']);

    // Validatie gegevens
    $errors = $user->ValidateUser();

    // Controleren of de gebruikersnaam en het wachtwoord zijn ingevuld
    if(empty($_POST['username']) || empty($_POST['password'])) {
        array_push($errors, "Vul zowel gebruikersnaam als wachtwoord in.");
    }

    // Controleren of de gebruikersnaam tussen 3 en 50 tekens lang is
    if(strlen($_POST['username']) < 3 || strlen($_POST['username']) > 50) {
        array_push($errors, "Gebruikersnaam moet tussen 3 en 50 tekens lang zijn.");
    }

    if(count($errors) == 0){
        // Register user
        $errors = $user->RegisterUser();
    }

    if(count($errors) > 0){
        $message = "";
        foreach ($errors as $error) {
            $message .= $error . "\\n";
        }

        echo "
        <script>alert('" . $message . "')</script>
        <script>window.location = 'register_form.php'</script>";

    } else {
        echo "
            <script>alert('" . "User registered" . "')</script>
            <script>window.location = 'login_form.php'</script>";
    }

}
?>

<form action="" method="POST">    
    <h4>Register here...</h4>
    <hr>
    
    <div>
        <label>Username</label>
        <input type="text"  name="username" />
    </div>
    <div >
        <label>Password</label>
        <input type="password"  name="password" />
    </div>
    <br />
    <div>
        <button type="submit" name="register-btn">Register</button>
    </div>
    <a href="index.php">Home</a>
</form>

</body>
</html>
