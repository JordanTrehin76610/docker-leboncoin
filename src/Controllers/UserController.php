<?php 
namespace App\Controllers;

use App\Models\User;
use App\Models\Database;
use PDO;
use PDOException;

class UserController
{

    public function register(): void {
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
                $stmt = $pdo->prepare("SELECT u_id, u_username, u_email, u_password, u_inscription FROM users WHERE u_email = :email");
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
        require_once __DIR__ . '/../views/login.php';   // On envoie ça à une vue
    }

    public function profil($id): void {
        try {
            $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, u_id, a_id FROM annonces WHERE u_id = :id");
            $stmt->execute(['id' => $id]);
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        require_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
    }

    public function logout(): void {
        session_start();
        session_destroy();
        session_unset();
        header("Location: index.php?url=home");
    }
}

?>