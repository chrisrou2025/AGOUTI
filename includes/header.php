<?php if (isset($_SESSION['login'])) { ?>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">TABLEAU DE BORD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my-profile.php">MON COMPTE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="issued-books.php">LIVRES EMPRUNTES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="change-password.php">CHANGER MON MOT DE PASSE</a>
                </li>
            </ul>
        </div>
        <div class="right-div">
            <a href="logout.php" class="btn btn-danger pull-right">DECONNEXION</a>
        </div>
    </nav>
<?php } else { ?>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="adminlogin.php">ADMINISTRATION</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">CREER UN COMPTE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">LOGIN LECTEUR</a>
                </li>
            </ul>
        </div>
    </nav>
<?php } ?>