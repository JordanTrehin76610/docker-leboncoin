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

    <h1 class="text-center mt-5">ETES VOUS SUR DE VOTRE DECISION ?????</h1>

    <div class="container text-center mt-5 mb-5">
        <div class="row">
            <div class="col">
                <form action="index.php?url=profil/<?= $_SESSION['id'] ?>" method="post">
                    <div class="text-center">
                        <a href="index.php?url=create" class="btn bouton"><button class="boutton px-5"
                                type="submit">NON</button></a>
                    </div>
                </form>
            </div>
            <div class="col">
                <form action="index.php?url=annihilationConfirm" method="post">
                    <div class="text-center">
                        <a href="index.php?url=create" class="btn bouton"><button class="boutton px-5"
                                type="submit">OUI</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>