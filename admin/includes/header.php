<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="#">
        <!--img src="assets/img/logo.png" /-->
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">TABLEAU DE BORD</a>
            </li>

            <!-- Menu Catégories -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    CATÉGORIES <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
                    <a class="dropdown-item" href="add-category.php">AJOUTER UNE CATÉGORIE</a>
                    <a class="dropdown-item" href="manage-categories.php">GÉRER LES CATÉGORIES</a>
                </div>
            </li>

            <!-- Menu Auteurs -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="authorsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    AUTEURS <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="authorsDropdown">
                    <a class="dropdown-item" href="add-author.php">AJOUTER UN AUTEUR</a>
                    <a class="dropdown-item" href="manage-authors.php">GÉRER LES AUTEURS</a>
                </div>
            </li>

            <!-- Menu Livres -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    LIVRES <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="booksDropdown">
                    <a class="dropdown-item" href="add-book.php">AJOUTER UN LIVRE</a>
                    <a class="dropdown-item" href="manage-books.php">GÉRER LES LIVRES</a>
                </div>
            </li>

            <!-- Menu Sorties -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="issuesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    SORTIES <i class="fa fa-angle-down"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="issuesDropdown">
                    <a class="dropdown-item" href="add-issue-book.php">AJOUTER UNE SORTIE</a>
                    <a class="dropdown-item" href="manage-issued-books.php">GÉRER LES SORTIES</a>
                </div>
            </li>

            <!-- Lecteurs -->
            <li class="nav-item">
                <a class="nav-link" href="reg-readers.php">LECTEURS</a>
            </li>

            <!-- Mot de passe -->
            <li class="nav-item">
                <a class="nav-link" href="change-password.php">CHANGER MOT DE PASSE</a>
            </li>
        </ul>
    </div>

    <!-- Bouton déconnexion -->
    <div class="right-div">
        <a href="logout.php" class="btn btn-danger">DECONNEXION</a>
    </div>
</nav>