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
        <h1>Connexion</h1>
        <form method="post" action="" novalidate>
            <span style="color: red !important; display: inline; float: none;">*</span>
            <span>Champ obligatoire à remplir</span><span
                class="ms-2 text-danger fst-italic fw-light"><?= $erreur["connexion"] ?? '' ?></span>
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