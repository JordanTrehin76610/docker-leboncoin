<?php 
namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function index(): void {
        require_once __DIR__ . '/../views/annonces.php';   // On envoie ça à une vue
    }

    public function create(): void {
        require_once __DIR__ . '/../views/create.php';   // On envoie ça à une vue
    }

    public function show(?int $id): void {
        require_once __DIR__ . '/../views/details.php';   // On envoie ça à une vue
    }
}

?>