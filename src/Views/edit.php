<?php 

$edit = 0; 

if (empty($_GET['url'])) {
    $url = ['home'];
} else {
    $url = explode("/", $_GET['url']);
}

if (!empty($url[2])) {
    $edit = $url[2];
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


<body class="d-flex flex-column min-vh-100">

    <?php include_once 'templates/navbar.php'; ?>

    <hr>

    <div class="mx-auto w-75 mt-5 mb-5">
        <div class="container text-center">
            <div class="row">
                <div class="col border-end">
                    <span
                        class="ms-2 text-danger fst-italic fw-light fs-5"><?= $_SESSION['erreur']["photo"] ?? '' ?></span>
                    <?php if (is_file($_SESSION['annonce']['a_picture'])){ ?>
                    <img src="<?= $_SESSION['annonce']['a_picture'] ?>" alt="Photo de l'article" class="photo border">
                    <?php } else { ?>
                    <img src="uploads/default.png" alt="Photo de l'article" class="photo border">
                    <?php } ?>
                    <button class="btn bouton-danger ms-5" type="submit"
                        onclick="location.href='index.php?url=edit/<?= $_SESSION['annonce']['a_id'] ?>/4'"><i
                            class="bi bi-pencil-fill"></i></button>
                    <?php if ($edit == 4) { ?>
                    <form method="post" action="" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-8"><input type="file" class="form-control" name="photo" id="photo">
                            </div>
                            <div class="col-4"><button class="btn btn-success border" type="submit"><i
                                        class="bi bi-check-lg"></i></button></div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
                <div class="col text-start ps-5">
                    <div class="row">
                        <h2><?= htmlspecialchars($_SESSION['annonce']['a_title']) ?><span
                                class="ms-2 text-danger fst-italic fw-light fs-5"><?= $_SESSION['erreur']["titre"] ?? '' ?></span><button
                                class="btn bouton-danger ms-5" type="submit"
                                onclick="location.href='index.php?url=edit/<?= $_SESSION['annonce']['a_id'] ?>/1'"><i
                                    class="bi bi-pencil-fill"></i></button></h2>
                    </div>
                    <?php if ($edit == 1) { ?>
                    <form method="post" action="" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-8"><input type="text" class="form-control" id="titre" name="titre"
                                    placeholder="Exemple: Table faite en saucisse" value=""></div>
                            <div class="col-4"><button class="btn btn-success border" type="submit"><i
                                        class="bi bi-check-lg"></i></button></div>
                        </div>
                    </form>
                    <?php } ?>
                    <div class="row">
                        <h4>Par <?= htmlspecialchars($_SESSION['annonce']['u_username']) ?></h4>
                    </div>
                    <div class="row">
                        <p><?= htmlspecialchars($_SESSION['annonce']['a_description']) ?><button
                                class="btn bouton-danger ms-5" type="submit"
                                onclick="location.href='index.php?url=edit/<?= $_SESSION['annonce']['a_id'] ?>/2'"><i
                                    class="bi bi-pencil-fill"></i></button>
                        <p class="ms-2 text-danger fst-italic fw-light fs-5">
                            <?= $_SESSION['erreur']["description"] ?? '' ?></p>
                        </p>
                    </div>
                    <?php if ($edit == 2) { ?>
                    <form method="post" action="" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-8"><textarea type="text" class="form-control pb-3" id="description"
                                    name="description"
                                    placeholder="Ecrivez votre message ici"><?= (isset($_POST['description'])) ? $_POST['description'] : ''; ?></textarea>
                            </div>
                            <div class="col-4"><button class="btn btn-success border" type="submit"><i
                                        class="bi bi-check-lg"></i></button></div>
                        </div>
                    </form>
                    <?php } ?>
                    <div class="row mt-5">
                        <p class="badge text-bg-success w-25 ms-3"><?= $_SESSION['annonce']['a_price'] ?>€<button
                                class="btn bouton-danger ms-5" type="submit"
                                onclick="location.href='index.php?url=edit/<?= $_SESSION['annonce']['a_id'] ?>/3'"><i
                                    class="bi bi-pencil-fill"></i></button><span
                                class="ms-5 text-danger fst-italic fw-light fs-5"><?= $_SESSION['erreur']["prix"] ?? '' ?></span>
                        </p>
                    </div>
                    <?php if ($edit == 3) { ?>
                    <form method="post" action="" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-8"><input type="number" class="form-control" id="prix" name="prix"
                                    placeholder="Exemple: 20 €" value="">
                            </div>
                            <div class="col-4"><button class="btn btn-success border" type="submit"><i
                                        class="bi bi-check-lg"></i></button></div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="index.php?url=annonces" class="btn bouton"><button class="boutton" type="submit">Voir les
                annonces</button></a>
    </div>

    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>