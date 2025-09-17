<?php 
namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{


    public function index(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $touteAnnonces = new Annonce();
        $touteAnnonces->findAll();

        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }

    public function search(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        }
        if (empty($_POST['search'])) {
            header('Location: index.php?url=annonces');
            exit;
        } 
        $touteAnnonces = new Annonce();
        $touteAnnonces->search($_POST['search']);

        require_once __DIR__ . '/../views/search.php';   // On envoie ça à une vue
    }


    public function create(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $annonce = new Annonce();
        $annonce->createAnnonce($_POST['titre'] ?? '', $_POST['description'] ?? '', floatval($_POST['prix'] ?? 0), $_FILES['photo'] ?? null, $_SESSION['id'] ?? 0);
        require_once __DIR__ . '/../views/create.php';   // On envoie ça à une vue
    }


    public function show(?int $id): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $detailAnnonce = new Annonce();
        $detailAnnonce->findById($id);
        require_once __DIR__ . '/../views/details.php';   // On envoie ça à une vue
    }


    public function delete(?int $id): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        }

        $delete = new Annonce();
        $delete->delete($id);
        header("Location: index.php?url=profil/".$_SESSION['id']);
        exit;
    }


    public function edit(?int $id): void {
        $_SESSION['erreur'] = [];
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $edit = new Annonce();
        if (isset($_POST['titre'])) {
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
        require_once __DIR__ . '/../views/edit.php';   // On envoie ça à une vue
    }


    public function addFav(int $idArticle) {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $fav = new Annonce();
        $fav->addFavorite($_SESSION['id'], $idArticle);
        header("Location: index.php?url=annonces");
        exit;
    }


    public function removeFav(int $idArticle) {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $fav = new Annonce();
        $fav->deleteFavorite($_SESSION['id'], $idArticle);
        header("Location: index.php?url=annonces");
        exit;
    }


    public function removeFavProfil(int $idArticle) {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $fav = new Annonce();
        $fav->deleteFavorite($_SESSION['id'], $idArticle);
        $fav->findAll();
        header("Location: index.php?url=profil/".$_SESSION['id']);
        exit;
    }


    public function achat(int $idArticle, float $prixArticle) {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        $achat = new Annonce();
        $result = $achat->achat($idArticle, $_SESSION['id'], $prixArticle);
        if ($result === true) {
            header("Location: index.php?url=profil/".$_SESSION['id']);
            exit;
        } else if (is_array($result)) {
            $errors = $result;
        }
        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }
}
?>