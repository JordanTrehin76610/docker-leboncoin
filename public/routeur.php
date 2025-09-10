<?php

use App\Controllers\HomeController;

if (empty($_GET['url'])) {
    $url = ['home'];
} else {
    $url = explode("/", $_GET['url']);
}

switch ($url[0]) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;
    case 'register':
        
        break;
    case 'login':
        
        break;
    case "profil":
        
        break;
    case "logout":
        
        break;
    case "annonces":

        break;
    case "create":

        break;
    case "details":

        break;
    default:
        header("Location: index.php"); // Redirection si l'id n'est pas un nombre ou est négatif ou supérieur au nombre de pokémon
        break;
}

?>