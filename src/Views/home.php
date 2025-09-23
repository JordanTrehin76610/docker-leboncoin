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
        <h1 class="my-5">Accueil</h1>
        <a href="index.php?url=annonces" class="btn bouton"><button class="boutton" type="submit">Voir toutes les
                annonces</button></a>

        <hr class="my-5 w-75 mx-auto">

        <h2 class="my-5">Rechercher une annonce</h2>
        <form action="index.php?url=search" method="POST" class="">
            <div class="container">
                <div class="row"><input type="text" name="search" placeholder="Rechercher une annonce"
                        class="border rounded mb-2 widthSearchBar mx-auto py-1"></div>
                <div class="row w-50 mx-auto mt-2"><button class="boutton widthSearch mx-auto" type="submit">Rechercher</button></div>
            </div>
        </form>

    </div>

    <?php include_once 'templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>