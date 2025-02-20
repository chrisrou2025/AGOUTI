<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');
} else {
    // Sinon
    // Apres soumission du formulaire de categorie
    if (isset($_POST['update'])) {
        // On recupere l'identifiant, le statut, le nom
        $catid = intval($_GET['catid']);
        $category = $_POST['category'];
        $status = $_POST['status'];

        // On prepare la requete de mise a jour
        $sql = "UPDATE tblcategory SET CategoryName=:category, Status=:status, UpdationDate=NOW() WHERE id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':catid', $catid, PDO::PARAM_STR);
        
        try {
            $query->execute();
            // On stocke dans $_SESSION le message "Categorie mise a jour"
            $_SESSION['msg'] = "Catégorie mise à jour avec succès";
            // On redirige l'utilisateur vers manage-categories.php
            header('location:manage-categories.php');
        } catch(PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour";
        }
    }

    // On prepare la requete de recherche des elements de la categorie dans tblcategory
    $catid = intval($_GET['catid']);
    $sql = "SELECT * FROM tblcategory WHERE id=:catid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':catid', $catid, PDO::PARAM_STR);
    // On execute la requete
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
}

?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Édition catégorie</title>
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
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page "Editer la categorie-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Éditer la catégorie</h4>
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
                                    <input class="form-control" type="text" name="category" 
                                           value="<?php echo htmlentities($result->CategoryName);?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Statut</label><br/>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" value="1" 
                                                <?php if($result->Status == 1) echo 'checked="checked"';?> />
                                            Active
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" value="0" 
                                                <?php if($result->Status == 0) echo 'checked="checked"';?> />
                                            Inactive
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>