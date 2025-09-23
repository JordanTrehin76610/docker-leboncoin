<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a href="index.php?url=home"><img src="uploads/logo.jpg" alt="Logo Leboncon"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end text-start" id="navbarSupportedContent">
            <hr>
            <?php if(isset($_SESSION['username'])) { ?>
            <div class="col-2">
                <div class="row">
                    <a href="index.php?url=logout" class="btn bouton"><button class="boutton"
                            type="button">Deconnexion</button></a>
                </div>
            </div>
            <div class="col-6 col-lg-1">
                <a href="index.php?url=profil" class="text-dark text-decoration-none">
                    <div class="row ms-lg-4 mt-2">
                        <i class="bi bi-person icone"></i>
                    </div>
                    <div class="row">
                        <p class="text-dark text-decoration-none">Bonjour <?= $_SESSION['username'] ?> </p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-1">
                <div class="row mt-2 pt-1 ms-lg-2">
                    <i class="bi bi-wallet"></i>
                </div>
                <div class="row mt-1">
                    <p class="text-dark text-decoration-none"><?= $_SESSION['monney'] ?> €</p>
                </div>
            </div>
            <?php } else { ?>
            <div class="col-2 col-lg-2">
                <div class="row mt-1 ms-lg-2">
                    <a href="index.php?url=register" class="btn bouton"><button class="boutton"
                            type="button">Inscription</button></a>
                </div>
            </div>
            <div class="col-6 col-lg-1">
                <a href="index.php?url=login" class="text-dark text-decoration-none">
                    <div class="row ms-lg-4 mt-2">
                        <i class="bi bi-person icone"></i>
                    </div>
                    <div class="row">
                        <p class="text-dark text-decoration-none">Se connecter</p>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</nav>