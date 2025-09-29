<?php 

namespace App\Controllers;

use App\Models\Annonce;

class HomeController
{
    public function index(): void {
        if(!isset($_SESSION['username']))  //Si l'utilisateur n'est pas connecté, on commence une session, question de sécurité
        { 
            session_start(); 
        }
        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès
        $lastAnnonce = new Annonce();
        $lastAnnonce->findLast();
        require_once __DIR__ . '/../Views/home.php';   // On envoie ça à une vue
    }

    public function page404(): void {
        $_SESSION['annonceEtat'] = "visually-hidden";
        require_once __DIR__ . '/../Views/page404.php';   // On envoie ça à une vue
    }
}

?>