<?php
session_start();
include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de creation
    if (isset($_POST['create'])) {
        // On recupere le nom et le statut de la categorie
        $category = $_POST['category'];
        $status = $_POST['status'];

        // On prepare la requete d'insertion dans la table tblcategory
        $sql = "INSERT INTO tblcategory(CategoryName,Status) VALUES(:category,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);

        // On execute la requete
        try {
            $query->execute();
            // On stocke dans $_SESSION le message correspondant au resultat de l'operation
            $_SESSION['msg'] = "Catégorie créée avec succès";
            header('location:manage-categories.php');
        } catch(PDOException $e) {
            $_SESSION['error'] = "Une erreur s'est produite, veuillez réessayer";
        }
    }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Ajout de catégories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Ajouter une catégorie</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Informations Catégorie
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Nom de la catégorie</label>
                                    <input class="form-control" type="text" name="category" required />
                                </div>

                                <div class="form-group">
                                    <label>Statut</label><br/>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" value="1" checked="checked" />
                                            Active
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" value="0" />
                                            Inactive
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" name="create" class="btn btn-info">Créer</button>
                            </form>
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
<?php } ?>