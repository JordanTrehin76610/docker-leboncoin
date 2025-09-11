<?php 
namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function index(): void {
        $touteAnnonces = new Annonce();
        $touteAnnonces->findAll();
        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }

    public function create(): void {
        session_start();
        $annonce = new Annonce();
        $annonce->createAnnonce($_POST['titre'] ?? '', $_POST['description'] ?? '', floatval($_POST['prix'] ?? 0), $_FILES['photo'] ?? null, $_SESSION['id'] ?? 0);
        require_once __DIR__ . '/../views/create.php';   // On envoie ça à une vue
    }

    public function show(?int $id): void {
        $detailAnnonce = new Annonce();
        $detailAnnonce->findById($id);
        require_once __DIR__ . '/../views/details.php';   // On envoie ça à une vue
    }
}

?>