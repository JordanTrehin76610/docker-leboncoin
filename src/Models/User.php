<?php 
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class User
{
    public function createUser(string $pseudo, string $email, string $mdpVerif) {
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";
        $_SESSION['registerEtat'] = " "; //On affiche l'alert de succès

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        $mdp = password_hash($mdpVerif, PASSWORD_DEFAULT);
        try {
        //Requete
        $sql = "INSERT INTO users (u_email, u_password, u_username, u_monney) VALUES (:email, :mdp, :pseudo, :monney)";
        $stmt = $pdo->prepare($sql); 
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
    }


    //VERIFIE SI L'EMAIL EXISTE DANS LA BASE DE DONNEES
    public function findByEmail(string $email): ?array {
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";
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

        $pdo = Database::getConnection();
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";

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
            $_POST['moneyEtat'] = " ";
            return true;

        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            return false;
        }
    }

    public function annihilation(int $id) {

        $pdo = Database::getConnection();
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";

        try {
            session_unset();
            session_destroy();

            // POUR SUPPRIMER UN PROFIL FAUT D'ABBORD SUPPRIMER TOUTES SES ANNONCES EN FAVORIS ET ACHAT 
            // PUIS SES ANNONCES PUIS LUI MEME 

            // Suppression des favoris de l'utilisateur
            $sql = "DELETE FROM FAVORIS WHERE a_id IN (SELECT a_id FROM annonces WHERE u_id = :id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

            $sql2 = "DELETE FROM FAVORIS WHERE u_id = :id";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute(['id' => $id]);


            // Suppression des achats de l'utilisateur
            $sql3 = "DELETE FROM ACHAT WHERE a_id IN (SELECT a_id FROM annonces WHERE u_id = :id)";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute(['id' => $id]);

            $sql4 = "DELETE FROM ACHAT WHERE ACHAT.u_id = :id";
            $stmt4 = $pdo->prepare($sql4);
            $stmt4->execute(['id' => $id]);


            // Suppression des annonces de l'utilisateur
            $sql5 = "DELETE FROM annonces WHERE u_id = :id";
            $stmt5 = $pdo->prepare($sql5);
            $stmt5->execute(['id' => $id]);


            // Suppression de l'utilisateur
            $sql6 = "DELETE FROM users WHERE u_id = :id";
            $stmt6 = $pdo->prepare($sql6);
            $stmt6->execute(['id' => $id]);
            header("Location: index.php?url=home");
        } catch (PDOException $e) {
            header("Location: index.php?url=page404");
        }
    }
}

?>