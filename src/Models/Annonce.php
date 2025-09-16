<?php 
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Annonce
{

    public function createAnnonce(string $titre, string $description, float $prix, ?array $photo, int $userId): bool {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
        $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex
        $_SESSION['erreur'] = [];


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if(isset($photo)) {
                if (empty($photo)) {
                    if ($photo['type'] !== 'image/jpeg' && $photo['type'] !== 'image/jpg' && $photo['type'] !== 'image/png' && $photo['type'] !== 'image/webp') {
                        $erreur['photo'] = "Mauvais type de fichier";
                    } else if ($photo['size'] > 9000000) {
                        $erreur['photo'] = "Fichier trop lourd, image de moins 8Mo uniquement";
                    }
                }
            }

            if(isset($titre)) {
                if (empty($titre)) {
                    $erreur["titre"] = "Veuillez inscrire un titre à votre article";
                } else if (strlen($titre) > 255) { //strlen regarde la longueur d'une chaîne
                    $erreur["titre"] = "Titre trop long";
                }
            }

            if(isset($prix)) {
                if (empty($prix)) {
                    $erreur["prix"] = "Veuillez inscrire un prix à votre article";
                } else if (!preg_match($regexPrix, $prix)) {
                    $erreur["prix"] = "Uniquement deux chiffres après la virgule";
                } else if ($prix < 0) {
                    $erreur["prix"] = "Veuillez inscrire un prix supérieur à 0 €";
                } else if ($prix > 999999999) {
                    $erreur["prix"] = "Prix trop grand";
                }
            }

            if(isset($description)) {
                if (empty($description)) {
                    $erreur["description"] = "Veuillez décrire votre articles";
                }
            }

            if(empty($erreur)){
                $tmpName = $photo['tmp_name'];
                $name = $photo['name'];
                $today = date("Ymd");  
                if (!isset($photo) || $photo['name'] == '') {
                    $chemin = 'uploads/default.png';
                } else {
                    $chemin = 'uploads/'.$userId.'_'.$today.'_'.$name;
                }
                move_uploaded_file($tmpName, __DIR__ . '/../../public/uploads/'.$userId.'_'.$today.'_'.$name); //Enregistre le fichier photo
                try {
                //Requete
                $stmt = $pdo->prepare("INSERT INTO annonces (a_title, a_description, a_price, a_picture, u_id) VALUES (:titre, :descriptions, :prix, :photo, :utilisateur)"); 
                // Exécution avec les valeurs
                $stmt->execute([
                    ':titre' => $titre,
                    ':descriptions' => $description,
                    ':prix' => $prix,
                    ':photo' => $chemin,
                    ':utilisateur' => $userId
                ]);
                header("Location: index.php?url=profil");
                exit;
                return true;    
                } catch (PDOException $e) {
                    return false;
                }
            } else {
                $_SESSION['erreur'] = $erreur;
                return false;
            }
            return false;
        }
        return false;
    }



    public function findAll(): array {

        $pdo = Database::getConnection();
        
        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }



    public function findById(int $id): ?array {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE a_id = :id");
            $stmt->execute(['id' => $id]);
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }

    public function delete(int $id): bool {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $sql = "DELETE FROM annonces WHERE a_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function editAnnonce(int $id, int $action, string $modif) {

        $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex
        $_SESSION['erreur'] = [];
        $_SESSION['annonce'] = [];

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE a_id = :id");
            $stmt->execute(['id' => $id]);
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;
        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        switch ($action) {
            case 10:
                if(empty($_POST['titre'])) {
                    $_SESSION['erreur']['titre'] = "Veuillez inscrire un titre";
                } else if (strlen($_POST['titre']) > 255) { //strlen regarde la longueur d'une chaîne
                    $_SESSION['erreur']['titre'] = "Titre trop long";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE annonces SET a_title = :titre WHERE a_id = :id");
                        $stmt->execute([
                            ':titre' => $modif,
                            ':id' => $id
                        ]);
                        header("Location: index.php?url=details/".$id);
                        exit;
                        return $_SESSION['annonce'];   
                    } catch (PDOException $e) {
                        return false;
                    }
                }
                break;
            case 20:
                if(empty($_POST['description'])) {
                    $_SESSION['erreur']['description'] = "Veuillez inscrire une description";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE annonces SET a_description = :description WHERE a_id = :id");
                        $stmt->execute([
                            ':description' => $modif,
                            ':id' => $id
                        ]);
                        header("Location: index.php?url=details/".$id);
                        exit;
                        return $_SESSION['annonce'];
                    } catch (PDOException $e) {
                        return false;
                    }
                }
                break;
            case 30:
                if(empty($_POST['prix'])) {
                    $_SESSION['erreur']['prix'] = "Veuillez inscrire un prix";
                } else if (!preg_match($regexPrix, $_POST['prix'])) {
                    $_SESSION['erreur']['prix'] = "Uniquement deux chiffres après la virgule";
                } else if ($_POST['prix'] < 0) {
                    $_SESSION['erreur']['prix'] = "Veuillez inscrire un prix supérieur à 0 €";
                } else if ($_POST['prix'] > 999999999) {
                    $_SESSION['erreur']['prix'] = "Prix trop grand";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE annonces SET a_price = :prix WHERE a_id = :id");
                        $stmt->execute([
                            ':prix' => $modif,
                            ':id' => $id
                        ]);
                        header("Location: index.php?url=details/".$id);
                        exit;
                        return $_SESSION['annonce'];    
                    } catch (PDOException $e) {
                        return false;
                    }
                }
                break;
            }
            return $_SESSION['annonce'];
        }
        return $_SESSION['annonce'];
    }
}

?>