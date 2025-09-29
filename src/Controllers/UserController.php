<?php 
namespace App\Controllers;

use App\Models\User;
use App\Models\Database;
use App\Models\Annonce;
use PDO;
use PDOException;

class UserController
{


    public function register(): void {

        //On recommence une nouvelle session, question de sécurité
        session_start();
        session_unset();
        session_destroy();
        session_start();

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
        $nom = "/^[a-z0-9.-]+$/i"; //Regex
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des données du formulaire
            $email = $_POST["email"] ?? '';
            $pseudo = $_POST["pseudo"] ?? '';
            $password = $_POST["mdp"] ?? '';
            $mdpVerif = $_POST["mdpVerif"] ?? '';

            $erreur = [];
            // On test les erreurs
            if(isset($email)) {
                if (empty($email)) {
                    $erreur["email"] = "Veuillez inscrire votre email";
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $erreur["email"] = "Mail non valide";
                } else if (strlen($email) > 50) { //strlen regarde la longueur d'une chaîne
                    $erreur["email"] = "Mail trop long";
                }
            }

            if(isset($pseudo)) {
                if (empty($pseudo)) {
                    $erreur["pseudo"] = "Veuillez inscrire votre pseudo";
                } else if (!preg_match($nom, $pseudo)) {
                    $erreur["pseudo"] = "Charactère non valide";
                } else if (strlen($pseudo) < 4) {
                    $erreur["pseudo"] = "Pseudo trop court";
                } else if (strlen($pseudo) > 25) {
                    $erreur["pseudo"] = "Pseudo trop long";
                }
            }

            if(isset($password)) {
                if (empty($password)) {
                    $erreur["mdp"] = "Veuillez rentrer votre mot de passe";
                } else if (strlen($password) < 6) {
                    $erreur["mdp"] = "Mot de passe trop court";
                } else if (strlen($password) > 20) {
                    $erreur["mdp"] = "Mot de passe trop long";
                }
            }

            if(isset($mdpVerif)) {
                if (empty($mdpVerif)) {
                    $erreur["mdpVerif"] = "Veuillez confirmer votre mot de passe";
                }
                else if (!empty($password) && ($password != $mdpVerif)) {
                    $erreur["mdpVerif"] = "Le mot de passe n'est pas le même";
                }
            }

            // Vérifier si l'email existe
            $stmt = $pdo->prepare("SELECT u_id FROM users WHERE u_email = :email");
            $stmt->execute(['email' =>  $email]);
            if ($stmt->fetch()) {
                $erreur["email"] = "Adresse email déjà utilisée";
            }

            // Vérifier si le pseudo existe
            $stmt = $pdo->prepare("SELECT u_id FROM users WHERE u_username = :pseudo");
            $stmt->execute(['pseudo' => $pseudo]);
            if ($stmt->fetch()) {
                $erreur["pseudo"] = "Pseudo déjà utilisé";
            }

            // Si pas d'erreur, on crée l'utilisateur
            if(empty($erreur)){
                $_SESSION['registerEtat'] = " "; //On affiche l'alert de succès
                $user = new User();
                $result = $user->createUser(
                    htmlspecialchars($pseudo ?? ''),
                    htmlspecialchars($email ?? ''),
                    htmlspecialchars($password ?? ''),
                    htmlspecialchars($mdpVerif ?? '')
                );
                if ($result === true) {
                    $_SESSION['registerEtat'] = " "; //On affiche l'alert de succès
                    header('Location: index.php?url=login');
                    exit;
                } elseif (is_array($result)) {
                    $erreur = $result;
                }
            }
        }
        require __DIR__ . '/../views/register.php'; // La vue peut utiliser $erreur
    }


    public function login(): void {

        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            session_start(); 
        } 

        $erreur = []; //On initialise le tableau d'erreurs

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

        $pdo = Database::getConnection(); //On se connecte à la base de données
        $sql = "SELECT * FROM users WHERE u_id = :id"; //On regarde si l'utilisateur avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête

        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès

        if (!$exists) { //Si le résultat est vide alors on l'envoie vers l'erreur 404
            $controller = new HomeController();
            $controller->page404();
            return;
        } else {

            // Récupère les infos utilisateur
            try {
                $stmt2 = $pdo->prepare("SELECT u_id, u_username, u_inscription, u_email, u_monney FROM users WHERE u_id = :id");
                $stmt2->execute(['id' => $id]);
                $user = $stmt2->fetch(PDO::FETCH_ASSOC);

                $_SESSION['id'] = $user['u_id'];
                $_SESSION['username'] = $user['u_username'];
                $_SESSION['date'] = $user['u_inscription'];
                $_SESSION['email'] = $user['u_email'];
                $_SESSION['monney'] = $user['u_monney'];

                // Récupère les annonces favorites de l'utilisateur
                $Fav = new Annonce();
                $Fav->findAll();

                // Récupère l'historique d'achat de l'utilisateur
                $achat = new Annonce();
                $achat->achatHistoric($id);

            } catch (PDOException $e) {
                // die("❌ Erreur SQL : " . $e->getMessage());
            }



            // Récupère les annonces de l'utilisateur
            try {
                $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
                $stmt = $pdo->prepare("SELECT a_title, a_picture, a_description, a_price, annonces.u_id, a_id, a_statut FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE annonces.u_id = :id");
                $stmt->execute(['id' => $id]);
                $annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['annonces'] = $annonces;
            } catch (PDOException $e) {
                // die("❌ Erreur SQL : " . $e->getMessage());
            }
            $_SESSION['messageMoney'] = ''; // Réinitialise le message après l'affichage
            require_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
        }
    }


    public function logout(): void {
        //On détruit la session
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?url=home");
    }


    public function addMoney(): void {

        if (!isset($_SESSION['username'])) { //Disponible que si connecté
            header('Location: index.php?url=page404');
            return;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex

                $amount = $_POST['money'] ?? '';

                if(isset($amount)) {
                    if (empty($amount)) {
                        $erreur = true; // Y'a rien
                        $_SESSION['messageMoney'] = "Veuillez entrer un montant.";
                        $_POST['moneyError'] = " "; // Sert juste à afficher le message d'erreur
                    } else if (!preg_match($regexPrix, $amount)) {
                        $erreur = true; // Montant avec trop de chiffre après la virgule
                        $_SESSION['messageMoney'] = "Veuillez entrer un montant valide (maximum 2 décimales).";
                        $_POST['moneyError'] = " "; // Sert juste à afficher le message d'erreur
                    } else if ($amount < 0) {
                        $erreur = true; // Montant trop faible
                        $_SESSION['messageMoney'] = "Le montant doit être positif.";
                        $_POST['moneyError'] = " "; // Sert juste à afficher le message d'erreur
                    } else if ($amount > 999999999) {
                        $erreur = true; // Montant trop élevé
                        $_SESSION['messageMoney'] = "Le montant est trop élevé.";
                        $_POST['moneyError'] = " "; // Sert juste à afficher le message d'erreur
                    }
                }

                if (isset($erreur) && $erreur == true) {
                    include_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
                    exit;
                } else {
                    $money = new User();
                    $result = $money->addMoney($_SESSION['id'], $amount);

                    if ($result == false) {
                        $_SESSION['messageMoney'] = "Erreur lors de l'ajout de fonds. Veuillez réessayer.";
                        $_POST['moneyError'] = " "; // Sert juste à afficher le message d'erreur
                        include_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
                    } else {
                        $_SESSION['messageMoney'] = "Fonds ajoutés avec succès !";
                        $_SESSION['monney'] += $amount; // Met à jour le montant dans la session
                        include_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
                    }
                }
            }
            include_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
        }
    }


    public function annihilation() {
        if (!isset($_SESSION['username'])) { //Disponible que si connecté
            header('Location: index.php?url=page404');
            return;
        }
        include_once __DIR__ . '/../views/annihilation.php';   // On envoie ça à une vue
    }

    public function annihilationConfirm($id): void {
        if (!isset($_SESSION['username'])) { //Disponible que si connecté
            header('Location: index.php?url=page404');
            return;
        } else {
            $deleteUser = new User();
            $deleteUser->annihilation($id);
        }
    }
}
?>