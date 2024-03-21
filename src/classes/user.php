<?php
namespace LoginOpdracht\classes;

use PDO;
use PDOException;


class User{

    // Eigenschappen 
    public $username;
    private $password;
    private $pdo;

    function __construct() {
        // Maak verbinding met de database
        $this->pdo = new PDO("mysql:host=localhost;dbname=login_oop", "root", "");
        // Zet de PDO-error-modus naar uitzonderingen
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    function SetPassword($password){
        $this->password = $password;
    }

    public function RegisterUser(){
        $status = false;
        $errors=[];
        if($this->username != "" || $this->password != ""){
            try {
                // SQL-query voor het invoegen van gebruiker
                $sql = "INSERT INTO user (username, password) VALUES (:name, :pwd)";
                $query = $this->pdo->prepare($sql);
                $query->execute([
                    'name' => $this->username,
                    'pwd' => $this->password
                ]);

                // Controleer of de gebruiker succesvol is toegevoegd
                if ($query->rowCount() > 0) {
                    // Gebruiker succesvol toegevoegd
                    $status = true;
                } else {
                    // Voeg een foutmelding toe aan $errors array
                    array_push($errors, "Er is een fout opgetreden tijdens het toevoegen van de gebruiker aan de database.");
                }
            } catch (PDOException $e) {
                // Vang eventuele fouten op die optreden bij het maken van de verbinding of het uitvoeren van de query
                array_push($errors, "Databasefout: " . $e->getMessage());
            }
        }
        return $errors;
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

    public function LoginUser(){
        // Zoek gebruiker in de tabel 'user'
        $stmt = $this->pdo->prepare("SELECT * FROM `user` WHERE `username` = ?");
        $stmt->execute([$this->username]);
        $user = $stmt->fetch();
    
        if ($user) {
            // Gebruiker gevonden, vul de sessie
            $_SESSION['username'] = $this->username;
            return true;
        } else {
            // Gebruiker niet gevonden
            return false;
        }
    }

   // Check if the user is already logged in
    public function IsLoggedin() {
        // Controleer of de sessievariabele voor ingelogde gebruiker is ingesteld
        return isset($_SESSION['username']);
    }

    public function GetUser($username){
        // Voorbereidde statement om de gebruiker op te halen met de opgegeven gebruikersnaam
        $stmt = $this->pdo->prepare("SELECT * FROM `user` WHERE `username` = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Controleer of de gebruiker is gevonden
        if ($user) {
            // Vul de eigenschappen van het User-object met waarden uit de database
            $this->username = $user['username'];
        } else {
            // Gebruiker niet gevonden, retourneer NULL
            return NULL;
        }
    }

    public function Logout(){
        // Verwijder de gebruiker uit de sessie
        unset($_SESSION['username']);
        // Stuur de gebruiker terug naar de indexpagina 
        header('location: index.php');
        exit; 
    }

    public function ShowUser() {
        // Implementeer de logica om gebruikersinformatie weer te geven
        echo "Gebruikersnaam: " . $this->username;
        // Voeg andere informatie toe die je wilt weergeven
    }

}
?>
