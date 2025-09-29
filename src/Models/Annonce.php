<?php 
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Annonce
{

    public function createAnnonce(string $titre, string $description, float $prix, ?string $chemin, int $userId, string $statut): bool {
        $_SESSION['annonceEtat'] = "visually-hidden"; //Masque les alerts de la page profil dés qu'on sort (ici on créer une annonce donc on est plus sur la page profil)
        $_SESSION['achatEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = " ";
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard        
        try {
            //Requete
            $stmt = $pdo->prepare("INSERT INTO annonces (a_title, a_description, a_price, a_picture, u_id, a_statut) VALUES (:titre, :descriptions, :prix, :photo, :utilisateur, :statut)"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':titre' => $titre,
                ':descriptions' => $description,
                ':prix' => $prix,
                ':photo' => $chemin,
                ':utilisateur' => $userId,
                ':statut' => $statut
            ]);

            header("Location: index.php?url=profil");
            exit;
            return true;
                
            } catch (PDOException $e) { //En cas d'erreur
                return false;
            } 
        }



    public function findAll(): array {

        $pdo = Database::getConnection(); //On se connecte à la base de donnée avec la fonction statique getConnection de la classe Database
        
        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC); //On stocke le résultat de la requête dans $annonce, fetchAll car plusieurs résultats
            $_SESSION['annonce'] = $annonce;

            //Pour virer les articles acheté de la liste principale
            $achat = $this->achatAll(); //On appelle la fonction pour avoir tous les achats
            foreach ($_SESSION['annonce'] as &$annonce) { // ForEach qui sert à mettre une clé is_achete à true ou false selon si l'annonce a été achetée ou non
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


        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }

    
    public function findLast(): array {

        $pdo = Database::getConnection(); //On se connecte à la base de donnée avec la fonction statique getConnection de la classe Database
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";
        $_SESSION['achatEtat'] = "visually-hidden";

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id ORDER BY a_id DESC");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

            //Pour virer les articles acheté de la liste principale
            $achat = $this->achatAll(); //On appelle la fonction pour avoir tous les achats
            foreach ($_SESSION['annonce'] as &$annonce) { // ForEach qui sert à mettre une clé is_achete à true ou false selon si l'annonce a été achetée ou non
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

            if (isset($_SESSION['id'])) {
                foreach ($_SESSION['annonce'] as &$annonce) { // Pour chaque annonce, on check si elle est en favoris
                    $annonceId = $annonce['a_id'];
                    $annonce['is_favorite'] = $this->isFavorite($annonceId);
                }
            }

            foreach ($_SESSION['annonce'] as $index => $annonce) { //Degage les annonces achetées
                if ($annonce['is_achete'] == true) {
                    unset($_SESSION['annonce'][$index]);
                }
            }

        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }


    public function search($search): array {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";
        $_SESSION['achatEtat'] = "visually-hidden";

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE a_title LIKE :search OR u_username LIKE :search");
            $stmt->execute(['search' => "%$search%"]);
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['annonce'] = $annonce;

            //Pour virer les articles acheté de la liste principale
            $achat = $this->achatAll(); //On appelle la fonction pour avoir tous les achats
            foreach ($_SESSION['annonce'] as &$annonce) { // ForEach qui sert à mettre une clé is_achete à true ou false selon si l'annonce a été achetée ou non
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


        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }



    public function findById(int $id): ?array {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard
        $_SESSION['annonceEtat'] = "visually-hidden";
        $_SESSION['annonceCreation'] = "visually-hidden";
        $_SESSION['achatEtat'] = "visually-hidden";

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE annonces.a_id = :id");
            $stmt->execute(['id' => $id]);
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC); //On utilise fetch car un seul résultat
            $_SESSION['annonce'] = $annonce;

        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['annonce'];
    }


    public function delete(int $id): bool {

        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $sql = "SELECT a_picture FROM annonces WHERE annonces.a_id = :id"; //On prepare la requête pour une question de sécurité
            $stmt = $pdo->prepare($sql); //On prépare la requête
            $stmt->execute(['id' => $id]); //On exécute la requête avec les valeurs
            $picture = $stmt->fetchColumn();
            if ($picture && is_file($picture) && $picture != 'uploads/default.png') {
                unlink($picture); //Supprime le fichier de l'annonce
            }

            $sql = "DELETE FROM annonces WHERE a_id = :id"; //On prepare la requête pour une question de sécurité
            $stmt = $pdo->prepare($sql); //On prépare la requête
            $stmt->execute(['id' => $id]); //On exécute la requête avec les valeurs
            $_SESSION['annonceEtat'] = " ";
            $_SESSION['annonceCreation'] = "visually-hidden";
            $_SESSION['achatEtat'] = "visually-hidden";
            return true;
        } catch (PDOException $e) { //En cas d'erreur
            return false;
        }
    }


    public function editAnnonce(int $id, int $action, string $modif) {

        $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex
        $_SESSION['annonceEtat'] = "visually-hidden"; //Masque les alerts de la page profil dés qu'on sort (ici on edite une annonce donc on est plus sur la page profil)
        $_SESSION['annonceCreation'] = "visually-hidden";
        $_SESSION['achatEtat'] = "visually-hidden";
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

            switch ($action) { //Switch pour savoir quel champ on modifie
                case 10:
                    if(isset($_POST['titre'])) {
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
                                $_SESSION['erreur'] = [];
                                header("Location: index.php?url=details/".$id);
                                exit;
                                return $_SESSION['annonce'];   
                            } catch (PDOException $e) {
                                return $_SESSION['annonce'];   
                            }
                        }
                    }
                    break;
                case 20:
                    if(isset($_POST['description'])) {
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
                                $_SESSION['erreur'] = [];
                                header("Location: index.php?url=details/".$id);
                                exit;
                                return $_SESSION['annonce'];
                            } catch (PDOException $e) {
                                return $_SESSION['annonce'];   
                            }
                        }
                    }
                    break;
                case 30:
                    if(isset($_POST['prix'])) {
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
                    $tmpName = $_FILES['photo']['tmp_name']; //Nom temporaire du fichier
                    $name = $_FILES['photo']['name']; //Nom du fichier
                    $today = date("Ymd");   //Date du jour
                    $chemin = 'uploads/'.$_SESSION['id'].'_'.$today.'_'.$name; //Chemin de la photo avec le nom du fichier
                    move_uploaded_file($tmpName, __DIR__ . '/../../public/uploads/'.$_SESSION['id'].'_'.$today.'_'.$name); //Enregistre le fichier photo
                    try {
                        $stmt = $pdo->prepare("UPDATE annonces SET a_picture = :photo WHERE a_id = :id"); 
                        $stmt->execute([
                            ':photo' => $chemin, //On stocke le chemin de la photo en base de donnée
                            ':id' => $id
                        ]);
                        $_SESSION['erreur'] = [];
                        header("Location: index.php?url=details/".$id);
                        exit;
                        return $_SESSION['annonce'];    
                    } catch (PDOException $e) {
                        return $_SESSION['annonce'];   
                    }
                    break;
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
        } catch (PDOException $e) { //En cas d'erreur
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
            //On ajoute l'achat dans la table Achat 
            $stmt = $pdo->prepare("INSERT INTO Achat (u_id, a_id) VALUES (:userId, :annonceId)"); 
            // Exécution avec les valeurs
            $stmt->execute([
                ':userId' => $userId,
                ':annonceId' => $annonceId
            ]);

            //On retire le montant de l'achat au solde de l'utilisateur
            $stmt2 = $pdo->prepare("SELECT u_monney FROM users WHERE u_id = :userId");
            $stmt2->execute([':userId' => $userId]);
            $monney = $stmt2->fetchColumn();
            $total = $monney - $price;

            $stmt3 = $pdo->prepare("UPDATE users SET u_monney = :total WHERE u_id = :userId");
            $stmt3->execute([
                ':total' => $total,
                ':userId' => $userId
            ]);

            //On passe le statut de l'annonce à "vendu"
            $stmt4 = $pdo->prepare("UPDATE annonces SET a_statut = 'vendu' WHERE a_id = :annonceId");
            $stmt4->execute([':annonceId' => $annonceId]);
            $_POST['achatEtat'] = " ";
            return true;

        } catch (PDOException $e) { //En cas d'erreur
            return false;
        }
    }


    public function achatHistoric(int $userId): array {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN Achat ON annonces.a_id = Achat.a_id INNER JOIN users ON annonces.u_id = users.u_id WHERE Achat.u_id = :userId");
            $stmt->execute(['userId' => $userId]);
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC); //On stocke le résultat de la requête dans $annonce, fetchAll car plusieurs résultats
            $_SESSION['achat'] = $annonce;
        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['achat'];
    }

    
    public function achatAll(): array {
        $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

        try {
            $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, annonces.u_id, annonces.a_id, u_username FROM annonces INNER JOIN Achat ON annonces.a_id = Achat.a_id INNER JOIN users ON annonces.u_id = users.u_id");
            $stmt->execute();
            $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC); //On stocke le résultat de la requête dans $annonce, fetchAll car plusieurs résultats
            $_SESSION['achat'] = $annonce;
        } catch (PDOException $e) { //En cas d'erreur
            die("❌ Erreur SQL : " . $e->getMessage());
        }
        return $_SESSION['achat'];
    }
}

?>