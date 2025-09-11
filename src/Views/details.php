<?php

session_start();

if (empty($_GET['url'])) {
    $url = ['home'];
} else {
    $url = explode("/", $_GET['url']);
}

use App\Models\Database;
$pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

try {
    $stmt = $pdo->prepare("SELECT a_title, a_description, a_price, a_picture, a_id, u_username, annonces.u_id FROM annonces INNER JOIN users ON annonces.u_id = users.u_id WHERE a_id = :id");
    $stmt->execute(['id' => $url[1]]);
    $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

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

    <div class="mx-auto w-75 mt-5 mb-5">
        <div class="container text-center">
            <div class="row">
                <div class="col border-end">
                    <img src="<?= $annonce['a_picture'] ?>" alt="Photo de l'article" class="photo border">
                </div>
                <div class="col text-start ps-5">
                    <div class="row">
                        <h2><?= htmlspecialchars($annonce['a_title']) ?></h2>
                    </div>
                    <div class="row">
                        <h4>Par <?= htmlspecialchars($annonce['u_username']) ?></h4>
                    </div>
                    <div class="row">
                        <p><?= $annonce['a_description'] ?></p>
                    </div>
                    <div class="row mt-5">
                        <p class="badge text-bg-success w-25"><?= $annonce['a_price'] ?>€</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="index.php?url=annonces" class="btn bouton"><button class="boutton" type="submit">Voir les
                annonces</button></a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>