<?php

$host = 'db';         // Nom du service MySQL dans Docker
$port = 3306;         // Port MySQL
$db   = 'leboncoin';  // Nom de la base
$user = 'root';       // Utilisateur
$pass = 'root';       // Mot de passe

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; //Génère le chemin de connection
$pdo = new PDO($dsn, $user, $pass); //Fais la connection   
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Gère les erreurs


$erreur = [];


try {
    // Requête SELECT
    $stmt = $pdo->query("SELECT u_email, u_password FROM users");

    // Parcourir les résultats
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "email: {$row['u_email']} - mdp: {$row['u_password']}<br>";
    }

} catch (PDOException $e) {
    die("❌ Erreur : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["email"])) {
        if (empty($_POST["email"])) {
            $erreur["email"] = "Veuillez inscrire votre email";
        } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $erreur["email"] = "Mail non valide";
        } else {
            foreach ($users as $utilisateur) {
                if ($utilisateur['mail'] == $_POST['email']) {
                    $_SESSION['role'] = $utilisateur['role']; //Pour le $_SESSION
                    $_SESSION['name'] = $utilisateur['name'];
                    break;
                } else {
                    $erreur["email"] = "Adresse mail incorrecte";
                }
            }
        }
    }

    if(isset($_POST["mdp"]) && empty($erreur["email"])) {
        foreach ($users as $index => $mdp) {
            if ($mdp['mail'] == $_POST['email']) {
                $indexUtilisateur = $index;
            }
        }
        if (empty($_POST["mdp"])) {
            $erreur["mdp"] = "Veuillez inscrire votre mot de passe";
        } else if (password_verify($_POST['mdp'], $users[$indexUtilisateur]['password'])) { //Verifie le mdp avec le mot de passe dans users.php
            $_SESSION['connexion'] = true; //Instruction pour éviter de se faire éjecter de l'espace
        } else {
            $erreur["mdp"] = "Mot de passe incorrect";
        }
    }

    if(empty($erreur)) { //Si pas d'erreur alors on redirige quand tout est bon
        header("Location: espace.php");
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
    <link rel="stylesheet" href="../../public/assets/css/style.css">
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
        <h1>Connexion</h1>
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
                        <label for="exampleFormControlInput1" class="form-label">Mot de passe</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["mdp"] ?? '' ?></span>
                        <input type="password" class="form-control" id="mdp" name="mdp"
                            placeholder="Exemple: MotDePasseSuperSecret0000">
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