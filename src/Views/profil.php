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
                <div class="col">
                    <div class="row text-start">
                        <p class="fs-3">Porte monnaie:</p>
                    </div>
                    <div class="row text-start">
                        <p><?= $_SESSION['monney'] ?> €</p>
                    </div>
                </div>
                <div class="col-8 col-lg">
                    <form action="index.php?url=money" method="post" novalidate>
                        <div class="row">
                            <button class="boutton" type="submit">Ajouter
                                des fonds</button>
                        </div>
                        <input type="number" class="form-control" id="money" name="money" placeholder="Exemple: 20 €"
                            value="<?= htmlspecialchars($_POST["money"] ?? "") ?>">
                    </form>
                </div>
            </div>
        </div>

        <hr>

        <div class="alert alert-primary <?= $_POST['moneyEtat'] ?? 'visually-hidden' ?>" role="alert">
            Votre solde a bien été mis à jour.
        </div>
        <div class="alert alert-danger <?= $_POST['moneyError'] ?? 'visually-hidden' ?>" role="alert">
            <?= $_SESSION['messageMoney'] ?? '' ?>
        </div>
        <div class="alert alert-primary <?= $_SESSION['annonceEtat'] ?? 'visually-hidden' ?>" role="alert">
            Votre annonce a bien été supprimée.
        </div>
        <div class="alert alert-primary <?= $_SESSION['annonceCreation'] ?? 'visually-hidden' ?>" role="alert">
            Votre annonce a bien été créée.
        </div>
        <div class="alert alert-success <?= $_SESSION['achatEtat'] ?? 'visually-hidden' ?>" role="alert">
            Achat effectué avec succès !
        </div>

        <div class="text-center">
            <a href="index.php?url=create" class="btn bouton"><button class="boutton" type="submit">Créer une
                    annonce</button></a>
        </div>

        <h2 class="mb-5">Vos annonces</h2>

        <?php if (!empty($_SESSION['annonces'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['annonces'] as $article) { 
                    if ($article['a_statut'] != 'vendu') {
                ?>
                <div class="col-lg-4 border">
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
                    <div class="row">
                        <div class="col">
                            <form action="index.php?url=delete/<?= $article['a_id'] ?>" method="post"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                                <button class="btn bouton-danger" type="submit"><i
                                        class="bi bi-trash3-fill"></i></button>
                            </form>

                        </div>
                        <div class="col">
                            <form action="index.php?url=edit/<?= $article['a_id'] ?>" method="post">
                                <button class="btn bouton-danger ms-5" type="submit"><i
                                        class="bi bi-pencil-fill"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

        <h2 class="mb-5">Vos vendus</h2>

        <?php if (!empty($_SESSION['annonces'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['annonces'] as $article) { 
                    if ($article['a_statut'] == 'vendu') {
                ?>
                <div class="col-lg-4 border">
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
                </div>
                <?php } else if ($article['a_statut'] == 'vendu') { ?>
                <p class="fs-3">Aucune annonce</p>
                <?php break; } } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

        <h2 class="mb-5">Vos favoris</h2>

        <?php if (!empty($_SESSION['annonce'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['annonce'] as $article) {
                    foreach ($_SESSION['achat'] as $achat) {
                        if ($article['a_id'] == $achat['a_id']) {
                            continue 2; // Si l'annonce a déjà été achetée, on passe à l'annonce suivante
                        }
                    }
                    if ($article['is_favorite'] == true) {?>
                <div class="col text-decoration-none text-dark col-lg-4 border">
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
                        <?php if ($article['u_id'] == $_SESSION['id']) { ?>
                        <div class="col-1">
                            <button type="submit" class="btn btn-secondary text-white"><i
                                    class="bi bi-bag-fill"></i></button>
                        </div>
                        <?php } else if ($article['a_price'] > $_SESSION['monney']) { ?>
                        <div class="col-1">
                            <button type="submit" class="btn btn-danger text-white"><i
                                    class="bi bi-bag-fill"></i></button>
                        </div>
                        <?php } else { ?>
                        <div class="col-1">
                            <form action="index.php?url=achat/<?= $article['a_id'] ?>/<?= $article['a_price'] ?>"
                                method="post">
                                <button type="submit" class="btn btn-success text-white"><i
                                        class="bi bi-bag-fill"></i></button>
                            </form>
                        </div>
                        <?php } ?>
                        <div class="col-lg-8 col-6 mb-2 ms-5">
                            <span class="badge text-bg-success"><?= $article['a_price'] ?>€</span>
                        </div>
                        <div class="col-1">
                            <?php if($article['is_favorite'] == true) { ?>
                            <form action="index.php?url=removeFavProfil/<?= $article['a_id'] ?>" method="post">
                                <button type="submit" class="btn btn-warning text-white"><i
                                        class="bi bi-star-fill"></i></button>
                            </form>
                            <?php } else { ?>
                            <form action="index.php?url=addFavProfil/<?= $article['a_id'] ?>" method="post">
                                <button type="submit" class="btn btn-warning text-white"><i
                                        class="bi bi-star"></i></button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

        <h2 class="mb-5">Votre historique d'achat</h2>

        <?php if (!empty($_SESSION['achat'])) { ?>
        <div class="container text-center mb-5">
            <div class="row">
                <?php foreach ($_SESSION['achat'] as $article) { ?>
                <div class="col-lg-4 border">
                    <?php $url = "index.php?url=details/". $article['a_id']?>
                    <a href='<?= $url ?>' class="text-decoration-none text-dark col-4">
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
                <?php } ?>
            </div>
        </div>
        <?php } else { ?>
        <p class="fs-3">Aucune annonce</p>
        <?php } ?>

        <!-- <form action="index.php?url=annihilation" method="post">
            <div class="text-center">
                <a href="index.php?url=create" class="btn bouton"><button class="boutton" type="submit">SUPPRIMER LE PROFIL</button></a>
            </div>
        </form> -->

    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>