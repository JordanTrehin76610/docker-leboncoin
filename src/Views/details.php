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

    <div class="mx-auto w-75 mt-5 mb-lg-5 mb-2">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg imageDetails mb-3">
                    <?php if (is_file($_SESSION['annonce']['a_picture'])){ ?>
                        <img src="<?= $_SESSION['annonce']['a_picture'] ?>" alt="Photo de l'article" class="photo border mb-3">
                    <?php } else { ?>
                        <img src="uploads/default.png" alt="Photo de l'article" class="photo border">
                    <?php } ?>
                </div>
                <div class="col text-start ps-lg-5">
                    <div class="row">
                        <h2><?= htmlspecialchars($_SESSION['annonce']['a_title']) ?></h2>
                    </div>
                    <div class="row">
                        <h4>Par <?= htmlspecialchars($_SESSION['annonce']['u_username']) ?></h4>
                    </div>
                    <div class="row">
                        <p><?= $_SESSION['annonce']['a_description'] ?></p>
                    </div>
                    <div class="row mt-lg-5 mt-2">
                        <p class="badge text-bg-success prixDetails"><?= $_SESSION['annonce']['a_price'] ?>€</p>
                    </div>
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