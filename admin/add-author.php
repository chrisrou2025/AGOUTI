<?php
session_start();
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = "";
}

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Traitement de l'ajout d'un auteur
    if (isset($_POST['add'])) {
        $author = $_POST['author'];

        // Vérifier si l'auteur existe déjà
        $sql = "SELECT * FROM tblauthors WHERE AuthorName=:author";
        $query = $dbh->prepare($sql);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['error'] = "Cet auteur existe déjà";
        } else {
            // Ajouter l'auteur
            $sql = "INSERT INTO tblauthors(AuthorName) VALUES(:author)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':author', $author, PDO::PARAM_STR);

            try {
                $query->execute();
                $_SESSION['msg'] = "Auteur ajouté avec succès";
                header('location:manage-authors.php');
            } catch (PDOException $e) {
                $_SESSION['error'] = "Une erreur est survenue";
            }
        }
    }
?>

    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <title>Gestion de bibliothèque en ligne | Ajout d'auteur</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('includes/header.php'); ?>

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="header-line">Ajouter un auteur</h4>
                    </div>
                </div>

                <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
                    <div class="alert alert-danger">
                        <strong>Erreur :</strong>
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php $_SESSION['error'] = ""; ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Informations Auteur
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post">
                                    <div class="form-group">
                                        <label>Nom de l'auteur</label>
                                        <input class="form-control" type="text" name="author" required />
                                    </div>

                                    <button type="submit" name="add" class="btn btn-info">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php } ?>