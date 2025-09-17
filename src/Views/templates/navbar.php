<navbar>
    <div class="container text-center">
        <div class="row pt-2 my-auto">
            <div class="col-7 text-start mt-2">
                <a href="index.php?url=home"><img src="uploads/logo.jpg" alt="Logo Leboncon"></a>
            </div>
            <?php if(isset($_SESSION['username'])) { ?>
            <div class="col-2">
                <div class="row">
                    <a href="index.php?url=logout" class="btn bouton"><button class="boutton"
                            type="button">Deconnexion</button></a>
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
            <div class="col-1">
                <div class="row mt-1">
                    <i class="bi bi-wallet"></i>
                </div>
                <div class="row mt-1">
                    <p class="text-dark text-decoration-none"><?= $_SESSION['monney'] ?> €</p>
                </div>
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