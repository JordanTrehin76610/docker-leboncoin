<?php

// session_start();

// use App\Models\Database;
// $pdo = Database::getConnection(); //On se connecte à la base et on stocke la connexion dans $pdo qu'on utilise plus tard

// $nom = "/^[a-z0-9.-]+$/i"; //Regex
// $regexPrix = '/^\d+(?:\.\d{1,2})?$/'; //Regex
// $erreur = [];
// // var_dump($_FILES);

// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     if(isset($_FILES['photo'])) {
//         if ($_FILES['photo']['name'] == '' ) {
//             $erreur['photo'] = "Veuillez choisir une photo pour votre article";
//         } else if ($_FILES['photo']['type'] !== 'image/jpeg' && $_FILES['photo']['type'] !== 'image/jpg' && $_FILES['photo']['type'] !== 'image/png' && $_FILES['photo']['type'] !== 'image/webp') {
//             $erreur['photo'] = "Mauvais type de fichier";
//         } else if ($_FILES['photo']['size'] > 9000000) {
//             $erreur['photo'] = "Fichier trop lourd, image de moins 8Mo uniquement";
//         }
//     }

//     if(isset($_POST["titre"])) {
//         if (empty($_POST["titre"])) {
//             $erreur["titre"] = "Veuillez inscrire un titre à votre article";
//         } else if (strlen($_POST["titre"]) > 255) { //strlen regarde la longueur d'une chaîne
//             $erreur["titre"] = "Titre trop long";
//         }
//     }

//     if(isset($_POST["prix"])) {
//         if (empty($_POST["prix"])) {
//             $erreur["prix"] = "Veuillez inscrire un prix à votre article";
//         } else if (!preg_match($regexPrix, $_POST["prix"])) {
//             $erreur["prix"] = "Uniquement deux chiffres après la virgule";
//         } else if ($_POST["prix"] < 0) {
//             $erreur["prix"] = "Veuillez inscrire un prix supérieur à 0 €";
//         } else if ($_POST["prix"] > 999999999) {
//             $erreur["prix"] = "Prix trop grand";
//         }
//     }

//     if(isset($_POST["description"])) {
//         if (empty($_POST["description"])) {
//             $erreur["description"] = "Veuillez décrire votre articles";
//         }
//     }

//     if(empty($erreur)){
//         $tmpName = $_FILES['photo']['tmp_name'];
//         $name = $_FILES['photo']['name'];
//         $chemin = 'uploads/'.$_SESSION['id'].'_'.$name;
//         move_uploaded_file($tmpName, __DIR__ . '/../../public/uploads/'.$_SESSION['id'].'_'.$name); //Enregistre le fichier photo
//         try {
//         //Requete
//         $stmt = $pdo->prepare("INSERT INTO annonces (a_title, a_description, a_price, a_picture, u_id) VALUES (:titre, :descriptions, :prix, :photo, :utilisateur)"); 
//         // Exécution avec les valeurs
//         $stmt->execute([
//             ':titre' => $_POST['titre'],
//             ':descriptions' => $_POST['description'],
//             ':prix' => $_POST['prix'],
//             ':photo' => $chemin,
//             ':utilisateur' => $_SESSION['id']
//         ]);
//         header("Location: index.php?url=profil");
//         } catch (PDOException $e) {
//         }
//     }

// }

?>

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

    <navbar>
        <div class="container text-center">
            <div class="row pt-2 my-auto">
                <div class="col-8 text-start mt-2">
                    <a href="index.php?url=home"><img src="uploads/logo.jpg" alt="Logo Leboncon"></a>
                </div>
                <?php if(isset($_SESSION['username'])) { ?>
                <div class="col-2">
                    <div class="row">
                        <a href="index.php?url=logout" class="btn bouton"><button class="boutton"
                                type="button">Déconnexion</button></a>
                    </div>
                </div>
                <div class="col-2">
                    <a href="index.php?url=profil" class="text-dark text-decoration-none">
                        <div class="row">
                            <i class="bi bi-person icone"></i>
                        </div>
                        <div class="row">
                            <p class="text-dark text-decoration-none">Bonjour <?= $_SESSION['username'] ?> </p>
                        </div>
                    </a>
                </div>
                <?php } else { ?>
                <div class="col-2">
                    <div class="row">
                        <a href="index.php?url=register" class="btn bouton"><button class="boutton"
                                type="button">Inscription</button></a>
                    </div>
                </div>
                <div class="col-2">
                    <a href="index.php?url=login" class="text-dark text-decoration-none">
                        <div class="row">
                            <i class="bi bi-person icone"></i>
                        </div>
                        <div class="row">
                            <p class="text-dark text-decoration-none">Se connecter</p>
                        </div>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </navbar>

    <hr>

    <div class="w-75 mx-auto mt-5">

        <h1>Créer votre annonce</h1>

        <form method="post" action="" enctype="multipart/form-data" novalidate>
            <span style="color: red !important; display: inline; float: none;">*</span>
            <span>Champ obligatoire à remplir</span>
            <div class="row mt-5">
                <div class="col">
                    <label for="exampleFormControlInput1" class="form-label">Photo</label><span
                        style="color: red !important; display: inline; float: none;">*</span><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $erreur["photo"] ?? '' ?></span>
                    <input type="file" class="form-control" name="photo" id="photo">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Titre</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["titre"] ?? '' ?></span>
                        <input type="text" class="form-control" id="titre" name="titre"
                            placeholder="Exemple: Table faite en saucisse"
                            value="<?= htmlspecialchars($_POST["titre"] ?? "") ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 text-start">
                        <label for="exampleFormControlInput1" class="form-label">Prix</label><span
                            style="color: red !important; display: inline; float: none;">*</span><span
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["prix"] ?? '' ?></span>
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
                            class="ms-2 text-danger fst-italic fw-light"><?= $erreur["description"] ?? '' ?></span>
                        <textarea type="text" class="form-control pb-3" id="description" name="description"
                            placeholder="Ecrivez votre message ici"><?= (isset($_POST['description'])) ? $_POST['description'] : ''; ?></textarea>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <a href="" class="btn bouton"><button class="boutton" type="submit">Publier votre
                            annonce</button></a>
                </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>


</html>