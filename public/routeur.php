<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AnnonceController;

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
        $controller = new UserController();
        $controller->register();
        break;
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;
    case "profil":
        $controller = new UserController();
        $controller->profil();
        break;
    case "logout":
        $controller = new UserController();
        $controller->logout();
        break;
    case "annonces":
        $controller = new AnnonceController();
        $controller->index();
        break;
    case "create":
        $controller = new AnnonceController();
        $controller->create();
        break;
    case "details":
        $controller = new AnnonceController();
        $controller->show($url[1]);
        break;
    default:
        header("Location: index.php"); // Redirection si l'id n'est pas un nombre ou est négatif ou supérieur au nombre de pokémon
        break;
}

?>