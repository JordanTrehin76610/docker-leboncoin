<?php

$host = 'db';         // Nom du service MySQL dans Docker
$port = 3306;         // Port MySQL
$db   = 'leboncoin';  // Nom de la base
$user = 'root';       // Utilisateur
$pass = 'root';       // Mot de passe

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; //Génère le chemin de connection
$pdo = new PDO($dsn, $user, $pass); //Fais la connection   
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Gère les erreurs

$nom = "/^[a-z.-]+$/i"; //Regex
$erreur = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["email"])) {
        if (empty($_POST["email"])) {
            $erreur["email"] = "Veuillez inscrire votre email";
        } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $erreur["email"] = "Mail non valide";
        } else if (strlen($_POST["email"]) > 50) { //strlen regarde la longueur d'une chaîne
            $erreur["email"] = "Mail trop long";
        }
    }

    if(isset($_POST["pseudo"])) {
        if (empty($_POST["pseudo"])) {
            $erreur["pseudo"] = "Veuillez inscrire votre pseudo";
        } else if (!preg_match($nom, $_POST["pseudo"])) {
            $erreur["pseudo"] = "Charactère non valide";
        } else if (strlen($_POST["pseudo"]) < 4) {
            $erreur["pseudo"] = "Pseudo trop court";
        } else if (strlen($_POST["pseudo"]) > 25) {
            $erreur["pseudo"] = "Pseudo trop long";
        }
    }

    if(isset($_POST["mdp"])) {
        if (empty($_POST["mdp"])) {
            $erreur["mdp"] = "Veuillez rentrer votre mot de passe";
        } else if (strlen($_POST["mdp"]) < 6) {
            $erreur["mdp"] = "Mot de passe trop court";
        } else if (strlen($_POST["mdp"]) > 20) {
            $erreur["mdp"] = "Mot de passe trop long";
        }
    }

    if(isset($_POST["mdpVerif"])) {
        if (empty($_POST["mdpVerif"])) {
            $erreur["mdpVerif"] = "Veuillez confirmer votre mot de passe";
        }
        else if (!empty($_POST["mdp"]) && ($_POST["mdp"] != $_POST["mdpVerif"])) {
            $erreur["mdpVerif"] = "Votre mot de passe n'est pas le même";
        }
    }

    if(empty($erreur)){
        $mdp = password_hash($_POST['mdpVerif'], PASSWORD_DEFAULT);
        try {
        //Requete
        $stmt = $pdo->prepare("INSERT INTO users (u_email, u_password, u_username) VALUES (:email, :mdp, :pseudo)"); 
        // Exécution avec les valeurs
        $stmt->execute([
            ':email' => $_POST['email'],
            ':mdp' => $mdp,
            ':pseudo' => $_POST['pseudo']
        ]);
        header("Location: index.php?url=login");
        } catch (PDOException $e) {
            $erreur["email"] = "Mail déjà utilisé";
        }
    }

}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leboncon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>


<body>

    <navbar>
        <div class="container text-center">
            <div class="row pt-2 my-auto">
                <div class="col-8 text-start mt-2">
                    <a href="index.php?url=home"><img src="uploads/logo.jpg" alt="Logo Leboncon"></a>
                </div>
                <div class="col-2">
                    <div class="row">
                        <a href="index.php?url=register" class="btn bouton"><button class="boutton"
                                type="button">S'inscrire</button></a>
                    </div>
                </div>
                <div class="col-2">
                    <div class="row">
                        <i class="bi bi-person icone"></i>
                    </div>
                    <div class="row">
                        <a href="index.php?url=login" class="text-dark text-decoration-none">
                            <p class="text-dark text-decoration-none">Se connecter</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </navbar>

    <hr>

    <div class="w-75 mx-auto mt-5">
        <form method="post" action="" novalidate>
            <span style="color: red !important; display: inline; float: none;">*</span>
            <span>Champ obligatoire à remplir</span>
            <div class="row mt-3">
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Email</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["email"] ?? '' ?></span>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Exemple: TheoduleLabit@email.com"
                            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Pseudo</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["pseudo"] ?? '' ?></span>
                        <input type="text" class="form-control" id="pseudo" name="pseudo"
                            placeholder="Exemple: TheoduleLabit@email.com"
                            value="<?= htmlspecialchars($_POST["pseudo"] ?? "") ?>">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Mot de passe</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["mdp"] ?? '' ?></span>
                        <input type="password" class="form-control" id="mdp" name="mdp"
                            placeholder="Exemple: TheoduleLabit@email.com"
                            value="<?= htmlspecialchars($_POST["mdp"] ?? "") ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Confirmer votre mot de
                            passe</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["mdpVerif"] ?? '' ?></span>
                        <input type="password" class="form-control" id="mdpVerif" name="mdpVerif"
                            placeholder="Exemple: TheoduleLabit@email.com"
                            value="<?= htmlspecialchars($_POST["mdpVerif"] ?? "") ?>">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="" class="btn bouton"><button class="boutton" type="submit">Valider
                        l'inscription</button></a>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>