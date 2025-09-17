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
                } else if (strlen($description) > 250) { 
                    $erreur["description"] = "Description trop longue";
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
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

            //Pour virer les articles acheté de la liste principale
            $achat = $this->achatAll(); //On appelle la fonction pour avoir tous les achats
            foreach ($_SESSION['annonce'] as &$annonce) {
                if (isset($achat) && !empty($achat)) {
                    foreach ($achat as $achatId) {
                        if ($annonce['a_id'] == $achatId['a_id']) { //On compare les id des annonces avec les id des achats
                            $annonce['is_achete'] = true;
                            break; // Si l'annonce a été achetée, on sort de la boucle interne
                        } else {
                            $annonce['is_achete'] = false;
                        }
                    }
                } else {
                    $annonce['is_achete'] = false;
                }
            }

            //Pour notifier des favoris de l'utilisateur, marche pas si pas connecter
            if (isset($_SESSION['id'])) {
                foreach ($_SESSION['annonce'] as &$annonce) { // Pour chaque annonce, on check si elle est en favoris
                    $annonceId = $annonce['a_id'];
                    $annonce['is_favorite'] = $this->isFavorite($annonceId);
                }
            }


        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }


    public function search($search): array {

        $pdo = Database::getConnection();
        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE a_title LIKE :search OR u_username LIKE :search");
            $stmt->execute(['search' => "%$search%"]);
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

            //Pour virer les articles acheté de la liste principale
            $achat = $this->achatAll(); //On appelle la fonction pour avoir tous les achats
            foreach ($_SESSION['annonce'] as &$annonce) {
                if (isset($achat) && !empty($achat)) {
                    foreach ($achat as $achatId) {
                        if ($annonce['a_id'] == $achatId['a_id']) { //On compare les id des annonces avec les id des achats
                            $annonce['is_achete'] = true;
                            break; // Si l'annonce a été achetée, on sort de la boucle interne
                        } else {
                            $annonce['is_achete'] = false;
                        }
                    }
                } else {
                    $annonce['is_achete'] = false;
                }
            }

            //Pour notifier des favoris de l'utilisateur, marche pas si pas connecter
            if (isset($_SESSION['id'])) {
                foreach ($_SESSION['annonce'] as &$annonce) { // Pour chaque annonce, on check si elle est en favoris
                    $annonceId = $annonce['a_id'];
                    $annonce['is_favorite'] = $this->isFavorite($annonceId);
                }
            }


        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }



    public function findById(int $id): ?array {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE annonces.a_id = :id");
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
            $sql = "SELECT a_picture FROM annonces WHERE annonces.a_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $picture = $stmt->fetchColumn();
            if ($picture && is_file($picture) && $picture != 'uploads/default.png') {
                unlink($picture); //Supprime le fichier de l'annonce
            }

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

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE annonces.a_id = :id");
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
                        return $_SESSION['annonce'];   
                    }
                }
                break;
            case 20:
                if(empty($_POST['description'])) {
                    $_SESSION['erreur']['description'] = "Veuillez inscrire une description";
                } else if (strlen($_POST['description']) > 100) { 
                    $_SESSION['erreur']['description'] = "Description trop longue";
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
                        return $_SESSION['annonce'];   
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
                        return $_SESSION['annonce'];   
                    }
                }
                break;
            case 40:
                //On supprime l'ancienne photo
                $stmt = $pdo->prepare("SELECT a_picture FROM annonces WHERE annonces.a_id = :id");
                $stmt->execute([':id' => $id]);
                $annonce = $stmt->fetch(PDO::FETCH_ASSOC);
                $picture = $annonce['a_picture'];
                if ($picture && is_file($picture) && $picture != 'uploads/default.png') {
                    unlink($picture); //Supprime le fichier de l'annonce
                }

                //On ajoute la nouvelle photo
                $tmpName = $_FILES['photo']['tmp_name'];
                $name = $_FILES['photo']['name'];
                $today = date("Ymd");  
                $chemin = 'uploads/'.$_SESSION['id'].'_'.$today.'_'.$name;
                move_uploaded_file($tmpName, __DIR__ . '/../../public/uploads/'.$_SESSION['id'].'_'.$today.'_'.$name); //Enregistre le fichier photo
                try {
                    $stmt = $pdo->prepare("UPDATE annonces SET a_picture = :photo WHERE a_id = :id");
                    $stmt->execute([
                        ':photo' => $chemin,
                        ':id' => $id
                    ]);
                    header("Location: index.php?url=details/".$id);
                    exit;
                    return $_SESSION['annonce'];    
                } catch (PDOException $e) {
                    return $_SESSION['annonce'];   
                }
                break;
            }
        }
        return $_SESSION['annonce'];
    }


    public function addFavorite(int $userId, int $annonceId): bool {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            //Requete
            $stmt = $pdo->prepare("INSERT INTO FAVORIS (u_id, a_id) VALUES (:userId, :annonceId)"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':userId' => $userId,
                ':annonceId' => $annonceId
            ]);
            return true;    
        } catch (PDOException $e) {
            return false;
        }
    }


    public function isFavorite(int $annonceId): bool {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            //Requete
            $stmt = $pdo->prepare("SELECT u_id, FAVORIS.a_id FROM FAVORIS WHERE u_id = :userId AND FAVORIS.a_id = :annonceId"); 
            // Exécution avec les valeurs
            
            $stmt->execute([
                ':userId' => $_SESSION['id'],
                ':annonceId' => $annonceId
            ]);
            $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête

            if ($exists) { //Agis selon le résultat
                return true; 
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }


    public function deleteFavorite(int $userId, int $annonceId): bool {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            //Requete
            $stmt = $pdo->prepare("DELETE FROM FAVORIS WHERE u_id = :userId AND a_id = :annonceId"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':userId' => $userId,
                ':annonceId' => $annonceId
            ]);
            return true;    
        } catch (PDOException $e) {
            return false;
        }
    }


    public function achat(int $annonceId, int $userId, float $price): bool {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            //Requete
            $stmt = $pdo->prepare("INSERT INTO Achat (u_id, a_id) VALUES (:userId, :annonceId)"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':userId' => $userId,
                ':annonceId' => $annonceId
            ]);

            $stmt2 = $pdo->prepare("SELECT u_monney FROM users WHERE u_id = :userId");
            $stmt2->execute([':userId' => $userId]);
            $monney = $stmt2->fetchColumn();
            $total = $monney - $price;

            $stmt3 = $pdo->prepare("UPDATE users SET u_monney = :total WHERE u_id = :userId");
            $stmt3->execute([
                ':total' => $total,
                ':userId' => $userId
            ]);
            return true;    
        } catch (PDOException $e) {
            return false;
        }
    }


    public function achatHistoric(int $userId): array {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN Achat ON annonces.a_id = Achat.a_id INNER JOIN users ON annonces.u_id = users.u_id WHERE Achat.u_id = :userId");
            $stmt->execute(['userId' => $userId]);
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['achat'] = $annonce;
        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['achat'];
    }

    
    public function achatAll(): array {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN Achat ON annonces.a_id = Achat.a_id INNER JOIN users ON annonces.u_id = users.u_id");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['achat'] = $annonce;
        } catch (PDOException $e) {
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['achat'];
    }
}

?>