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


<body class="d-flex flex-column min-vh-100">

<?php include_once 'templates/navbar.php'; ?>

    <hr>

    <div class="w-75 mx-auto mt-5">

        <h1>Créer votre annonce</h1>

        <form method="post" action="" enctype="multipart/form-data" novalidate>
            <span style="color: red !important; display: inline; float: none;">*</span>
            <span>Champ obligatoire à remplir</span>
            <div class="row mt-5">
                <div class="col">
                    <label for="exampleFormControlInput1" class="form-label">Photo</label><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $_SESSION['erreur']["photo"] ?? '' ?></span>
                    <input type="file" class="form-control" name="photo" id="photo">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Titre</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $_SESSION['erreur']["titre"] ?? '' ?></span>
                        <input type="text" class="form-control" id="titre" name="titre"
                            placeholder="Exemple: Table faite en saucisse"
                            value="<?= htmlspecialchars($_POST["titre"] ?? "") ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Prix</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $_SESSION['erreur']["prix"] ?? '' ?></span>
                        <input type="number" class="form-control" id="prix" name="prix" placeholder="Exemple: 20 €"
                            value="<?= htmlspecialchars($_POST["prix"] ?? "") ?>">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Description</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $_SESSION['erreur']["description"] ?? '' ?></span>
                        <textarea type="text" class="form-control pb-3" id="description" name="description"
                            placeholder="Ecrivez votre message ici"><?= (isset($_POST['description'])) ? $_POST['description'] : ''; ?></textarea>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <a href="" class="btn bouton"><button class="boutton" type="submit">Publier votre
                            annonce</button></a>
                </div>
            </div>

        </form>
    </div>

    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>