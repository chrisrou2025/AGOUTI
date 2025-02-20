<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Traitement de la mise à jour
    if(isset($_POST['update'])) {
        $authorid = intval($_GET['authorid']);
        $authorname = $_POST['authorname'];
        
        // Mise à jour des informations
        $sql = "UPDATE tblauthors SET AuthorName=:authorname, UpdationDate=NOW() WHERE id=:authorid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':authorname', $authorname, PDO::PARAM_STR);
        $query->bindParam(':authorid', $authorid, PDO::PARAM_STR);
        
        try {
            $query->execute();
            $_SESSION['msg'] = "Auteur mis à jour avec succès";
            header('location:manage-authors.php');
        } catch(PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour";
        }
    }

    // Récupération des informations de l'auteur
    $authorid = intval($_GET['authorid']);
    $sql = "SELECT * FROM tblauthors WHERE id=:authorid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':authorid', $authorid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Édition auteur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Éditer l'auteur</h4>
            </div>
        </div>
        
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
                                <input class="form-control" type="text" name="authorname" 
                                       value="<?php echo htmlentities($result->AuthorName);?>" required />
                            </div>

                            <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
