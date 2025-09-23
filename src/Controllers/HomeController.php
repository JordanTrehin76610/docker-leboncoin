<?php 

namespace App\Controllers;

class HomeController
{
    public function index(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        }
        $_SESSION['registerEtat'] = "visually-hidden"; //On affiche l'alert de succès
        require_once __DIR__ . '/../Views/home.php';   // On envoie ça à une vue
    }

    public function page404(): void {
        $_SESSION['annonceEtat'] = "visually-hidden";
        require_once __DIR__ . '/../Views/page404.php';   // On envoie ça à une vue
    }
}

?>