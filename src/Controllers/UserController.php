<?php 
namespace App\Controllers;

use App\Models\User;
use App\Models\Database;
use PDO;
use PDOException;

class UserController
{


    public function register(): void {

        session_start();
        session_unset();
        session_destroy();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $result = $user->createUser(
                $_POST['pseudo'] ?? '',
                $_POST['email'] ?? '',
                $_POST['mdp'] ?? '',
                $_POST['mdpVerif'] ?? ''
            );
            if ($result === true) {
                header('Location: index.php?url=login');
                exit;
            } elseif (is_array($result)) {
                $errors = $result;
            }
        }
        require __DIR__ . '/../views/register.php'; // La vue peut utiliser $errors
    }


    public function login(): void {

        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 

        $erreur = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérification email
        $email = $_POST["email"] ?? '';
        $mdp = $_POST["mdp"] ?? '';

        if (empty($email)) {
            $erreur["email"] = "Veuillez inscrire votre email";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur["email"] = "Mail non valide";
        }

        // Vérification mot de passe
        if (empty($mdp)) {
            $erreur["mdp"] = "Veuillez inscrire votre mot de passe";
        }

        // Si pas d'erreur sur email/mdp, on va chercher l'utilisateur
        if (empty($erreur)) {
            try {
                $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
                $stmt = $pdo->prepare("SELECT u_id, u_username, u_email, u_password, u_inscription, u_monney FROM users WHERE u_email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                $verifyEmail = new User();
                $result = $verifyEmail->findByEmail($email); //Utilise la méthode findByEmail de la classe User

                if ($result == null) { //Si l'email existe
                    if (password_verify($mdp, $user['u_password'])) { //Si le mot de passe est bon
                        //stocke dans session
                        $_SESSION['id'] = $user['u_id'];
                        $_SESSION['username'] = $user['u_username'];
                        $_SESSION['date'] = $user['u_inscription'];
                        $_SESSION['email'] = $user['u_email'];
                        $_SESSION['monney'] = $user['u_monney'];

                        // Redirection
                        header("Location: index.php?url=profil/" . $user['u_id']);
                        exit;

                    } else {
                        $erreur['connexion'] = "Adresse mail ou mot de passe incorrect";
                    }
                } else {
                    $erreur['connexion'] = "Adresse mail ou mot de passe incorrect";
                }
            } catch (PDOException $e) {
                // return $erreur;
                // die("❌ Erreur : " . $e->getMessage());
            }
        }
    }
        require_once __DIR__ . '/../views/login.php';   // On envoie ça à une vue
    }


    public function profil($id): void {

        // Récupère les infos utilisateur
        try {
            $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
            $stmt = $pdo->prepare("SELECT u_id, u_username, u_inscription, u_email, u_monney FROM users WHERE u_id = :id");
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['id'] = $user['u_id'];
            $_SESSION['username'] = $user['u_username'];
            $_SESSION['date'] = $user['u_inscription'];
            $_SESSION['email'] = $user['u_email'];
            $_SESSION['monney'] = $user['u_monney'];

        } catch (PDOException $e) {
            // die("❌ Erreur SQL : " . $e->getMessage());
        }



        // Récupère les annonces de l'utilisateur
        try {
            $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
            $stmt = $pdo->prepare("SELECT a_title, a_picture, a_description, a_price, annonces.u_id, a_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE annonces.u_id = :id");
            $stmt->execute(['id' => $id]);
            $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonces'] = $annonces;
        } catch (PDOException $e) {
            // die("❌ Erreur SQL : " . $e->getMessage());
        }
        require_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
        $_SESSION['messageMoney'] = ''; // Réinitialise le message après l'affichage
    }


    public function logout(): void {
        session_unset();
        session_destroy();
        header("Location: index.php?url=home");
    }


    public function addMoney(): void {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $money = new User();
            $result = $money->addMoney($_SESSION['id'], $_POST['money']);

            if ($result == false) {
                // Gérer l'erreur (par exemple, afficher un message d'erreur)
                $_SESSION['messageMoney'] = "Erreur lors de l'ajout de fonds. Veuillez réessayer.";
            } else {
                $_SESSION['messageMoney'] = "Fonds ajoutés avec succès !";
            }
        }
        header('Location: index.php?url=profil/' . $_SESSION['id']);
        exit;
    }
}

?>