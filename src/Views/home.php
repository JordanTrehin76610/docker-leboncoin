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


<body class="d-flex flex-column min-vh-100">

    <?php include_once 'templates/navbar.php'; ?>

    <hr>

    <div class="text-center">

        <h1 class="my-3 mb-5">Rechercher une annonce</h1>
        <form action="index.php?url=search" method="POST" class="">
            <div class="container">
                <div class="row"><input type="text" name="search" placeholder="Rechercher une annonce"
                        class="border rounded mb-2 widthSearchBar mx-auto py-1"></div>
                <div class="row w-50 mx-auto mt-3"><button class="boutton widthSearch mx-auto"
                        type="submit">Rechercher</button></div>
            </div>
        </form>
        <a href="index.php?url=annonces" class="btn bouton"><button class="boutton" type="submit">Voir toutes les
                annonces</button></a>

        <hr class="my-3 w-75 mx-auto">

        <h2 class="my-2 mb-4">Les dernières annonces</h2>

        <?php if (!empty($_SESSION['annonce'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php $i = 0;
                foreach ($_SESSION['annonce'] as $article) { ?>
                <div class="col-lg-4 border">
                    <?php $url = "index.php?url=details/". $article['a_id']?>
                    <a href='<?= $url ?>' class="text-decoration-none text-dark col-4">
                        <div class="row">
                            <div class="col-7 text-start overflow-x-hidden text-nowrap ">
                                <p><?= $article['a_title'] ?></p>
                            </div>
                            <div class="col-5 text-end overflow-x-hidden text-wrap">
                                <p><?= $article['u_username'] ?></p>
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
                </div>
                <?php $i++; ?>
                <?php if ($i == 3) break; } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

    </div>

    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>


</html>