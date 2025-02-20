<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

if (strlen($_SESSION['rdid']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:index.php');
} else {
    // Récupération de l'ID du lecteur depuis la session
    $readerId = $_SESSION['rdid'];

    // On veut savoir combien de livres ce lecteur a emprunte
    // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
    // Requête pour compter le nombre total de livres empruntés
    $sql = "SELECT COUNT(id) as totalBooks 
                 FROM tblissuedbookdetails 
                 WHERE ReaderId = :readerId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // On stocke le résultat dans une variable
    // Stockage du nombre total d'emprunts
    $totalEmprunts = $result->totalBooks;

    // On veut savoir combien de livres ce lecteur n'a pas rendu
    // On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0
    // Requête pour compter les livres non rendus (ReturnStatus = 0)
    $sql = "SELECT COUNT(id) as nonRendus 
                 FROM tblissuedbookdetails 
                 WHERE ReaderId = :readerId 
                 AND ReturnStatus = 0";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // On stocke le résultat dans une variable
    // Stockage du nombre de livres non rendus
    $livresNonRendus = $result->nonRendus;
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        .card-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* .custom-card {
            transition: transform 0.3s;
        }

        .custom-card:hover {
            transform: translateY(-5px);
        } */

        .borrowed-card {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }

        .return-card {
            background: linear-gradient(45deg, #e74a3b, #be2617);
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">TABLEAU DE BORD UTILISATEUR</h4>
                </div>
            </div>
            <div class="row">
                <!-- Carte des livres empruntés -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="card text-white custom-card borrowed-card mb-3">
                        <div class="card-body text-center">
                            <i class="fas fa-bars card-icon"></i>
                            <h3 class="card-title">Mes Emprunts</h3>
                            <h1 class="display-4"><?php echo $totalEmprunts; ?></h1>
                            <p class="card-text">Total des livres empruntés</p>
                        </div>
                    </div>
                </div>
                <!-- Carte des livres non rendus -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="card text-white custom-card return-card mb-3">
                        <div class="card-body text-center">
                            <i class="fas fa-recycle card-icon"></i>
                            <h3 class="card-title">Livres à Rendre</h3>
                            <h1 class="display-4"><?php echo $livresNonRendus; ?></h1>
                            <p class="card-text">Livres non encore rendus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>