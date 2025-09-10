<?php 
namespace App\Controllers;

use App\Models\User;

class UserController
{

    public function register(): void {
        require_once __DIR__ . '/../views/register.php';   // On envoie ça à une vue
    }

    public function login(): void {
        require_once __DIR__ . '/../views/login.php';   // On envoie ça à une vue
    }

    public function profil(): void {
        require_once __DIR__ . '/../views/profil.php';   // On envoie ça à une vue
    }

    public function logout(): void {
        
    }
}

?>