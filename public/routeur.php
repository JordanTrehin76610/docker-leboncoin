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
        if (isset($_SESSION['username'])) { //Disponible que si connecté
            $controller = new HomeController();
            $controller->page404();
        } else {
            $controller = new UserController();
            $controller->profil($url[1]);
        }
        break;
    case "logout":
        if (isset($_SESSION['username'])) { //Disponible que si connecté
            $controller = new HomeController();
            $controller->page404();
        } else {
            $controller = new UserController();
            $controller->logout();
        }
        break;
    case "annonces":
        $controller = new AnnonceController();
        $controller->index();
        break;
    case "create":
        if (isset($_SESSION['username'])) { //Disponible que si connecté
            $controller = new HomeController();
            $controller->page404();
        } else {
            $controller = new AnnonceController();
            $controller->create();
        }
        break;
    case "details":
        if (isset($url[1]) && is_numeric($url[1]) && $url[1] > 0) { //Disponible que si id est un nombre et est positif
            $controller = new AnnonceController();
            $controller->show($url[1]);
        } else {
            $controller = new HomeController();
            $controller->page404();
        }
        break;
}

?>