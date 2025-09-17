<?php 
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class User
{
    public function createUser(string $pseudo, string $email, string $password, string $mdpVerif) {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
        $nom = "/^[a-z0-9.-]+$/i"; //Regex

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

        if(empty($erreur)){


            $mdp = password_hash($mdpVerif, PASSWORD_DEFAULT);
            try {
            //Requete
            $stmt = $pdo->prepare("INSERT INTO users (u_email, u_password, u_username, u_monney) VALUES (:email, :mdp, :pseudo, :monney)"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':email' => $email,
                ':mdp' => $mdp,
                ':pseudo' => $pseudo,
                ':monney' => 0
            ]);
            header("Location: index.php?url=login");
                return true;
            } catch (PDOException $e) {
                return false;
            }
        } else {
            return $erreur;
        }
    }


    //VERIFIE SI L'EMAIL EXISTE DANS LA BASE DE DONNEES
    public function findByEmail(string $email): ?array {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        $stmt = $pdo->prepare("SELECT u_id, u_username, u_email, u_password, u_inscription, u_monney FROM users WHERE u_email = :email");
        $stmt->execute(['email' => $email]); //Verification si l'email existe
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            return null; //L'email existe donc on renvoie aucune erreur :)
        } else {
            $erreur['connexion'] = "Adresse mail ou mot de passe incorrect";
            return $erreur;
        }
    }


    public function addMoney(int $id, float $amount): bool {

        $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex

        if(isset($amount)) {
            if (empty($amount)) {
                return false; // Y'a rien
            } else if (!preg_match($regexPrix, $amount)) {
                return false; // Montant avec trop de chiffre après la virgule
            } else if ($amount < 0) {
                return false; // Montant trop faible
            } else if ($amount > 999999999) {
                return false; // Montant trop élevé
            }
        }

        $pdo = Database::getConnection();

        try {
            // Commencer une transaction

            // Récupérer le solde actuel de l'utilisateur
            $stmt = $pdo->prepare("SELECT u_monney FROM users WHERE u_id = :id");
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $total = $user['u_monney'] + $amount;

            // Mettre à jour le solde de l'utilisateur
            $stmt = $pdo->prepare("UPDATE users SET u_monney = :newMonney WHERE u_id = :id");
            $stmt->execute([
                'newMonney' => $total,
                'id' => $id
            ]);

            return true;

        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            return false;
        }
    }
}

?>