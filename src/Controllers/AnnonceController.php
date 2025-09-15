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
    
}

?>