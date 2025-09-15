<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AnnonceController;
use App\Models\Database;

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

        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0

        $pdo = Database::getConnection(); //On se connecte à la base de données
        $sql = "SELECT * FROM users WHERE u_id = :id"; //On regarde si l'utilisateur avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête

        if ($exists) { //Si le résultat n'est pas vide alors go
            session_start();
            $controller = new UserController();
            $controller->profil($id ?? $_SESSION['id']); // Si pas d'id dans l'url, on prend l'id de la session
        } else {
            $controller = new HomeController();
            $controller->page404();
        }
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
        if (!isset($_SESSION['username'])) { //Disponible que si connecté
            $controller = new HomeController();
            $controller->page404();
        } else {
            $controller = new AnnonceController();
            $controller->create();
        }
        break;

    case "details":

        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0

        $pdo = Database::getConnection(); //On se connecte à la base de données
        $sql = "SELECT * FROM annonces WHERE a_id = :id"; //On regarde si l'annonce avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête


        if ($exists) { //Si le résultat n'est pas vide alors go
            $controller = new AnnonceController();
            $controller->show($id);
        } else {
            $controller = new HomeController();
            $controller->page404();
        }
        break;

    case "delete":

        $id = $url[1] ?? 0; // Récupère l'id dans l'url s'il y en a un, sinon 0

        $pdo = Database::getConnection(); //On se connecte à la base de données
        $sql = "SELECT * FROM annonces WHERE a_id = :id"; //On regarde si l'annonce avec cet id existe
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exists = $stmt->fetchColumn(); //On remplie la variable $exists avec le résultat de la requête

        if ($exists) { //Si le résultat n'est pas vide alors go
            $controller = new AnnonceController();
            $controller->delete($id);
        } else {
            $controller = new HomeController();
            $controller->page404();
        }
        break;
}

?>