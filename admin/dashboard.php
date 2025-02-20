<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
} else {
    // Requête pour compter le nombre total de livres
    $sql = "SELECT COUNT(*) as total FROM tblbooks";
    $query = $dbh->prepare($sql);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $totalBooks = $result['total'];

    // Requête pour compter le nombre de livres en prêt
    $sql1 = "SELECT COUNT(*) as total FROM tblissuedbookdetails WHERE ReturnStatus = 0";
    $query1 = $dbh->prepare($sql1);
    $query1->execute();
    $result1 = $query1->fetch(PDO::FETCH_ASSOC);
    $issuedBooks = $result1['total'];

    // Requête pour compter le nombre de livres retournés
    $sql2 = "SELECT COUNT(*) as total FROM tblissuedbookdetails WHERE ReturnStatus = 1";
    $query2 = $dbh->prepare($sql2);
    $query2->execute();
    $result2 = $query2->fetch(PDO::FETCH_ASSOC);
    $returnedBooks = $result2['total'];

    // Requête pour compter le nombre de lecteurs
    $sql3 = "SELECT COUNT(*) as total FROM tblreaders";
    $query3 = $dbh->prepare($sql3);
    $query3->execute();
    $result3 = $query3->fetch(PDO::FETCH_ASSOC);
    $totalReaders = $result3['total'];

    // Requête pour compter le nombre d'auteurs
    $sql4 = "SELECT COUNT(*) as total FROM tblauthors";
    $query4 = $dbh->prepare($sql4);
    $query4->execute();
    $result4 = $query4->fetch(PDO::FETCH_ASSOC);
    $totalAuthors = $result4['total'];

    // Requête pour compter le nombre de catégories
    $sql5 = "SELECT COUNT(*) as total FROM tblcategory";
    $query5 = $dbh->prepare($sql5);
    $query5->execute();
    $result5 = $query5->fetch(PDO::FETCH_ASSOC);
    $totalCategories = $result5['total'];
?>

    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
        <title>Gestion de bibliothèque en ligne | Dashboard Administration</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <!-- On inclue le fichier header.php qui contient le menu de navigation-->
        <?php include('includes/header.php'); ?>

        <!-- Titre de la page -->
        <div class="content-wrapper py-4">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4 class="header-line">TABLEAU DE BORD ADMINISTRATION</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Les cartes sont bien structurées, on garde la même structure -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-primary">
                                                    <i class="fa fa-book fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($totalBooks); ?></h2>
                                                </div>
                                                <h5 class="card-title">Nombre total de livres</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte pour les livres en prêt -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-warning">
                                                    <i class="fa fa-recycle fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($issuedBooks); ?></h2>
                                                </div>
                                                <h5 class="card-title">Livres en prêt</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte pour les livres retournés -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-success">
                                                    <i class="fa fa-check-circle fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($returnedBooks); ?></h2>
                                                </div>
                                                <h5 class="card-title">Livres retournés</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte pour le nombre de lecteurs -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-info">
                                                    <i class="fa fa-users fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($totalReaders); ?></h2>
                                                </div>
                                                <h5 class="card-title">Nombre de lecteurs</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte pour le nombre d'auteurs -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-danger">
                                                    <i class="fa fa-user fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($totalAuthors); ?></h2>
                                                </div>
                                                <h5 class="card-title">Nombre d'auteurs</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Carte pour le nombre de catégories -->
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <div class="text-secondary">
                                                    <i class="fa fa-list fa-4x mb-3"></i>
                                                    <h2><?php echo htmlentities($totalCategories); ?></h2>
                                                </div>
                                                <h5 class="card-title">Nombre de catégories</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php } ?>