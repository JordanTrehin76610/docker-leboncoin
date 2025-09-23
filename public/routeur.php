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

        session_start();
        if (empty($_SESSION['id'])) {
            $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        } else {
            $id = $_SESSION['id'];
        }
        $controller = new UserController();
        $controller->profil($id ?? $_SESSION['id']); // Si pas d'id dans l'url, on prend l'id de la session
        break;

    case "logout":

        session_start();
        if (!isset($_SESSION['username'])) { //Disponible que si connecté
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

        session_start();
        $controller = new AnnonceController();
        $controller->create();
        break;

    case "details":

        session_start();
        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->show($id);
        break;

    case "delete":

        session_start();
        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->delete($id);
        break;

    case "edit":

        session_start();
        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->edit($id);
        break;

    case "money":
        session_start();
        $controller = new UserController();
        $controller->addMoney();
        break;

    case "addFav":
        session_start();
        $idArticle = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->addFav($idArticle);
        break;

    case "removeFav":
        session_start();
        $idArticle = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->removeFav($idArticle);
        break;

    case "removeFavProfil":
        session_start();
        $idArticle = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->removeFavProfil($idArticle);
        break;

    case "achat":
        session_start();
        $idArticle = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0
        $prixArticle = $url[2] ?? 0; // Récupère le prix dans l'url s'il y en a un, sinon 0
        $controller = new AnnonceController();
        $controller->achat($idArticle, $prixArticle);
        break;

    case "search":
        $controller = new AnnonceController();
        $controller->search();
        break;

    case "page404":
        $controller = new HomeController();
        $controller->page404();
        break;

    case "annihilation":
        session_start();
        $controller = new UserController();
        $controller->annihilation();
        break;

    case "annihilationConfirm":
        session_start();
        $controller = new UserController();
        $controller->annihilationConfirm($_SESSION['id']);
        break;
}

?>