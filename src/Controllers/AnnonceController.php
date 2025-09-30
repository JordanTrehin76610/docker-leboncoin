<?php 
namespace App\Controllers;

use App\Models\Annonce;
use App\Models\Database;

class AnnonceController
{


    public function index(): void {
        if(!isset($_SESSION['username'])) //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            session_start(); 
        } 
        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès
        $touteAnnonces = new Annonce();
        $touteAnnonces->findAll(); //On récupère toutes les annonces

        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }

    public function search(): void {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            session_start(); 
        }
        if (empty($_POST['search'])) { //Si le champ de recherche est vide, on le redirige vers la page annonces
            header('Location: index.php?url=annonces');
            exit;
        }
        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès
        $touteAnnonces = new Annonce();
        $touteAnnonces->search($_POST['search']);

        require_once __DIR__ . '/../views/search.php';   // On envoie ça à une vue
    }


    public function create(): void {

        if(!isset($_SESSION['username'])) //Si l'utilisateur n'est pas connecté, on le dégage vers la page 404
        { 
            header("Location: index.php?url=page404");
            exit;
        }

        $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex
        $_SESSION['erreur'] = []; //On initialise le tableau d'erreurs
        $erreur = [];
        $_SESSION['achatEtat'] = "visually-hidden";
        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès

        //ON RECUPERE LES INFOS DU FORMULAIRE, SI ABSENT ON MET UNE VALEUR PAR DEFAUT
        $photo = $_FILES['photo'] ?? null;
        $titre = $_POST['titre'] ?? '';
        $prix = $_POST['prix'] ?? 0;
        $description = $_POST['description'] ?? '';
        $userId = $_SESSION['id'] ?? 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //ON DEFINIE LES ERREURS EVENTUELLES
            if(isset($photo)) {
                if (empty($photo)) {
                    if ($photo['type'] !== 'image/jpeg' && $photo['type'] !== 'image/jpg' && $photo['type'] !== 'image/png' && $photo['type'] !== 'image/webp') {
                        $erreur['photo'] = "Mauvais type de fichier";
                    } else if ($photo['size'] > 9000000) {
                        $erreur['photo'] = "Fichier trop lourd, image de moins 8Mo uniquement";
                    }
                }
            } else if (!isset($_FILES['photo'])) {
                $erreur['photo'] = "Fichier trop lourd, image de moins 8Mo uniquement";
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

            // SI PAS D'ERREUR ON CREE L'ANNONCE
            if(empty($erreur)){
                $tmpName = $photo['tmp_name'];
                $name = $photo['name'];
                $today = date("Ymd");  
                if (!isset($photo) || $photo['name'] == '') { //Si l'utilisateur n'a pas mis de photo, on met une photo par défaut
                    $chemin = 'uploads/default.png';
                } else {
                    $chemin = 'uploads/'.$userId.'_'.$today.'_'.$name;
                }
                move_uploaded_file($tmpName, __DIR__ . '/../../public/uploads/'.$userId.'_'.$today.'_'.$name); //Enregistre le fichier photo
                $annonce = new Annonce();
                $annonce->createAnnonce(htmlspecialchars($titre ?? ''), htmlspecialchars($description ?? ''), floatval($prix ?? 0), $chemin, $userId, 'A vendre'); //htlmmspecialchars pour éviter les failles xss
            } else {
                $_SESSION['erreur'] = $erreur;
            }
        }
        require_once __DIR__ . '/../views/create.php';   // On envoie ça à une vue
    }


    public function show(?int $id): void {

        $pdo = Database::createInstancePDO(); //On se connecte à la base de données
        $sql = "SELECT * FROM annonces WHERE a_id = :id"; //On regarde si l'annonce avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête, fetchColumn renvoie une seule colonne
        
        if ($exists) { //Si le résultat n'est pas vide alors go
        $detailAnnonce = new Annonce();
        $detailAnnonce->findById($id);
        } else {
            $controller = new HomeController();
            $controller->page404();
            return;
        }
        require_once __DIR__ . '/../views/details.php';   // On envoie ça à une vue
    }


    public function delete(?int $id): void {
        if(!isset($_SESSION['username'])) //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            session_start(); 
        }

        $pdo = Database::createInstancePDO(); //On se connecte à la base de données
        $sql = "SELECT a_id, u_id FROM annonces WHERE a_id = :id"; //On regarde si l'annonce avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetch(); //On remplie la variable $exists avec le résultat de la requête

        if (!$exists || ($_SESSION['id'] != $exists['u_id'])) { //Si le résultat est vide ou que l'utilisateur n'est pas le bon alors on l'envoie vers l'erreur 404
            header("Location: index.php?url=page404");
            exit;        
        } else {
            $delete = new Annonce();
            $delete->delete($id);
            $_SESSION['annonceEtat'] = " ";
            $_SESSION['achatEtat'] = "visually-hidden";
            header("Location: index.php?url=profil/".$_SESSION['id']);
            exit;
        }
    }


    public function edit(?int $id): void {
        $_SESSION['erreur'] = []; //On initialise le tableau d'erreurs
        
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité 
        { 
            session_start(); 
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
            $pdo = Database::createInstancePDO(); //On se connecte à la base de données
            $sql = "SELECT a_id, u_id FROM annonces WHERE a_id = :id"; //On regarde si l'annonce avec cet id existe
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $exists = $stmt->fetch(); //On remplie la variable $exists avec le résultat de la requête
            
        if(!$exists || ($_SESSION['id'] != $exists['u_id'])) { //Si le résultat est vide ou que l'utilisateur n'est pas le bon alors on l'envoie vers l'erreur 404
            header("Location: index.php?url=page404");
            exit;        
        } else {
            $edit = new Annonce();
            if (isset($_POST['titre'])) { //On vérifie quel champ a été modifié
                $edit->editAnnonce($id, 10, $_POST['titre']);
            } else if (isset($_POST['description'])) {
                $edit->editAnnonce($id, 20, $_POST['description']);
            } else if (isset($_POST['prix'])) {
                $edit->editAnnonce($id, 30, $_POST['prix']);
            } else if (isset($_FILES['photo'])) {
                if ($_FILES['photo']['type'] !== 'image/jpeg' && $_FILES['photo']['type'] !== 'image/jpg' && $_FILES['photo']['type'] !== 'image/png' && $_FILES['photo']['type'] !== 'image/webp') {
                    $_SESSION['erreur']['photo'] = "Mauvais type de fichier";
                } else if ($_FILES['photo']['size'] > 9000000) {
                    $_SESSION['erreur']['photo'] = "Fichier trop lourd, image de moins 8Mo uniquement";
                } else if (empty($_FILES['photo']['name'])) {
                    $_SESSION['erreur']['photo'] = "Veuillez choisir une photo";
                } else {
                    $edit->editAnnonce($id, 40, $_FILES['photo']['name']);
                }
            } else {
                $edit->editAnnonce($id, 30, '');
            }
        }}
        require_once __DIR__ . '/../views/edit.php';   // On envoie ça à une vue
    }


    public function addFav(int $idArticle) {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            header("Location: index.php?url=page404");
            exit;
        } else {
            $fav = new Annonce();
            $fav->addFavorite($_SESSION['id'], $idArticle);
            header("Location: index.php?url=annonces");
            exit;
        }
    }


    public function removeFav(int $idArticle) {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            header("Location: index.php?url=page404");
            exit; 
        } else {
            $fav = new Annonce();
            $fav->deleteFavorite($_SESSION['id'], $idArticle);
            header("Location: index.php?url=annonces");
            exit;
        }
    }


    public function removeFavProfil(int $idArticle) {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            header("Location: index.php?url=page404");
            exit; 
        } else {
            $fav = new Annonce();
            $fav->deleteFavorite($_SESSION['id'], $idArticle);
            $fav->findAll();
            header("Location: index.php?url=profil/".$_SESSION['id']);
            exit;
        }
    }


    public function achat(int $idArticle, float $prixArticle) {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            header("Location: index.php?url=page404");
            exit; 
        } else {
            $achat = new Annonce();
            $result = $achat->achat($idArticle, $_SESSION['id'], $prixArticle);
            if ($result === true) {
                $_SESSION['achatEtat'] = " ";
                header("Location: index.php?url=profil/".$_SESSION['id']);
                exit;
            } else if (is_array($result)) {
                $errors = $result;
            }
        }
        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }
}
?>