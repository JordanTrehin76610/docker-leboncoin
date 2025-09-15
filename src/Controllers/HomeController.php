<?php 

namespace App\Controllers;

class HomeController
{
    public function index(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        }  
        require_once __DIR__ . '/../Views/home.php';   // On envoie ça à une vue
    }

    public function page404(): void {
        if(!isset($_SESSION['username'])) 
        { 
            session_start(); 
        } 
        require_once __DIR__ . '/../Views/page404.php';   // On envoie ça à une vue
    }
}

?>