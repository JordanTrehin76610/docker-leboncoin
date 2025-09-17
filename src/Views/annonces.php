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

    <?php include_once 'templates/navbar.php'; ?>

    <hr>

    <div class="mx-auto w-75">
        <h1 class="my-5">Les annonces</h1>

        <?php if (!empty($_SESSION['annonce'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['annonce'] as $article) { ?>
                <div class="col text-decoration-none text-dark col-4 border">
                    <?php $url = "index.php?url=details/". $article['a_id']?>
                    <a href='<?= $url ?>' class="text-decoration-none text-dark">
                        <div class="row">
                            <div class="col-7 text-start overflow-x-hidden text-nowrap ">
                                <p><?= htmlspecialchars($article['a_title']) ?></p>
                            </div>
                            <div class="col-5 text-end overflow-x-hidden text-wrap">
                                <p><?= htmlspecialchars($article['u_username']) ?></p>
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
                            <div class="col mt-2 description overflow-x-hidden">
                                <p><?= $article['a_description'] ?></p>
                            </div>
                        </div>
                    </a>
                    <div class="row">
                        <div class="col-9 mb-2 ms-5">
                            <span class="badge text-bg-success"><?= $article['a_price'] ?>€</span>
                        </div>
                        <div class="col-1">
                            <?php if($article['is_favorite'] == true) { ?>
                            <form action="index.php?url=removeFav/<?= $article['a_id'] ?>" method="post">
                                <button type="submit" class="btn btn-warning text-white"><i
                                        class="bi bi-star-fill"></i></button>
                            </form>
                            <?php } else { ?>
                            <form action="index.php?url=addFav/<?= $article['a_id'] ?>" method="post">
                                <button type="submit" class="btn btn-warning text-white"><i
                                        class="bi bi-star"></i></button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
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