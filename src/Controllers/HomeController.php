<?php 

namespace App\Controllers;

class HomeController
{
    public function index(): void {
        require_once __DIR__ . '/../Views/home.php';   // On envoie ça à une vue
    }
}

?>