<?php

session_start();

$host = 'db';         // Nom du service MySQL dans Docker
$port = 3306;         // Port MySQL
$db   = 'leboncoin';  // Nom de la base
$user = 'root';       // Utilisateur
$pass = 'root';       // Mot de passe

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; //Génère le chemin de connection
$pdo = new PDO($dsn, $user, $pass); //Fais la connection   
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Gère les erreurs

try {
    $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, u_id, a_id FROM annonces");
    $stmt->execute();
    $annonce = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Erreur SQL : " . $e->getMessage());
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
                <?php if(isset($_SESSION['username'])) { ?>
                <div class="col-2">
                    <div class="row">
                        <a href="index.php?url=logout" class="btn bouton"><button class="boutton"
                                type="button">Déconnexion</button></a>
                    </div>
                </div>
                <div class="col-2">
                    <a href="index.php?url=profil" class="text-dark text-decoration-none">
                        <div class="row">
                            <i class="bi bi-person icone"></i>
                        </div>
                        <div class="row">
                            <p class="text-dark text-decoration-none">Bonjour <?= $_SESSION['username'] ?> </p>
                        </div>
                    </a>
                </div>
                <?php } else { ?>
                <div class="col-2">
                    <div class="row">
                        <a href="index.php?url=register" class="btn bouton"><button class="boutton"
                                type="button">Inscription</button></a>
                    </div>
                </div>
                <div class="col-2">
                    <a href="index.php?url=login" class="text-dark text-decoration-none">
                        <div class="row">
                            <i class="bi bi-person icone"></i>
                        </div>
                        <div class="row">
                            <p class="text-dark text-decoration-none">Se connecter</p>
                        </div>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </navbar>


    <hr>

    <div class="mx-auto w-75">
    <h1 class="my-5">Les annonces</h1>

    <?php if (!empty($annonce)) { ?>
    <div class="container text-center mb-5">
        <div class="row">
            <?php foreach ($annonce as $article) { ?>
            <?php $url = "index.php?url=details/". $article['a_id']?>
            <a href='<?= $url ?>'>
            <div class="col-4 border">
                <div class="row">
                    <div class="col">
                        <p><?= htmlspecialchars($article['a_title']) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col cadre">
                        <img src="<?= $article['a_picture'] ?>" alt="Photo de l'article" class="photo border">
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-2">
                        <p><?= $article['a_description'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <span class="badge text-bg-success"><?= $article['a_price'] ?>€</span>
                    </div>
                </div>
            </div>
            </a>
            <?php } ?>
        </div>
    </div>
    <?php } else { ?>
    <p class="fs-3">Aucune annonce</p>
    <?php } ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>


</html>