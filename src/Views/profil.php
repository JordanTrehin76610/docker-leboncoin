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
<?php include_once 'templates/navbar.php'; ?>
    <hr>

    <div class="w-75 mx-auto mt-5">

        <h1>Votre profil</h1>

        <div class="container text-center mt-5">
            <div class="row">
                <div class="col">
                    <div class="row text-start">
                        <p class="fs-3">Pseudo:</p>
                    </div>
                    <div class="row text-start">
                        <p><?= $_SESSION['username'] ?></p>
                    </div>
                </div>
                <div class="col">
                    <div class="row text-start">
                        <p class="fs-3">Mail:</p>
                    </div>
                    <div class="row text-start">
                        <p><?= $_SESSION['email'] ?></p>
                    </div>
                </div>
                <div class="col">
                    <div class="row text-start">
                        <p class="fs-3">Date d'inscription:</p>
                    </div>
                    <div class="row text-start">
                        <p><?= $_SESSION['date'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="text-center">
            <a href="index.php?url=create" class="btn bouton"><button class="boutton" type="submit">Créer une
                    annonce</button></a>
        </div>


        <h2 class="mb-5">Vos annonces</h2>

        <?php if (!empty($_SESSION['annonces'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['annonces'] as $article) { ?>
                <div class="col-4 border">
                    <?php $url = "index.php?url=details/". $article['a_id']?>
                    <a href='<?= $url ?>' class="text-decoration-none text-dark col-4">
                        <div class="row">
                            <div class="col text-start overflow-x-hidden text-nowrap">
                                <p><?= htmlspecialchars($article['a_title']) ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col cadre">
                                <?php if (is_file($article['a_picture'])){ ?>
                                    <img src="<?= $article['a_picture'] ?>" alt="Photo de l'article" class="photo border">
                                <?php } else { ?>
                                    <img src="uploads/default.png" alt="Photo de l'article" class="photo border">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt-2 description overflow-x-hidden text-wrap">
                                <p><?= $article['a_description'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-2">
                                <span class="badge text-bg-success"><?= $article['a_price'] ?>€</span>
                            </div>
                        </div>
                    </a>
                    <button class="btn bouton-danger" type="submit"
                        onclick="location.href='index.php?url=delete/<?= $article['a_id'] ?>'"><i
                            class="bi bi-trash3-fill"></i></button>
                    <button class="btn bouton-danger ms-5" type="submit"
                        onclick="location.href='index.php?url=edit/<?= $article['a_id'] ?>'"><i class="bi bi-pencil-fill"></i></button>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>