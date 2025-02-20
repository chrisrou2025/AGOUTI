<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');

// Si l'utilisateur est déconnecté
if (strlen($_SESSION['alogin']) == 0) {
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:index.php');
} else {
    // Gestion de la suppression (changement de statut)
    if (isset($_GET['del'])) {
        // On recupere l'identifiant de la catégorie a supprimer
        $id = $_GET['del'];
        
        // On prepare la requete de suppression (mise à jour du statut)
        $sql = "UPDATE tblcategory SET Status=0 WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        
        // On execute la requete
        try {
            $query->execute();
            // On informe l'utilisateur du resultat de l'operation
            $_SESSION['delmsg'] = "Catégorie désactivée avec succès";
            // On redirige l'utilisateur vers la page manage-categories.php
            header('location:manage-categories.php');
        } catch(PDOException $e) {
            $_SESSION['error'] = "Erreur lors de la désactivation";
        }
    }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Gestion catégories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php');?>
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Gestion des catégories</h4>
                </div>
            </div>
            
            <!-- Messages de succès/erreur -->
            <?php if($_SESSION['error']!="") { ?>
                <div class="alert alert-danger">
                    <strong>Erreur :</strong> 
                    <?php echo htmlentities($_SESSION['error']); ?>
                    <?php $_SESSION['error']=""; ?>
                </div>
            <?php } ?>
            <?php if($_SESSION['msg']!="") { ?>
                <div class="alert alert-success">
                    <strong>Succès :</strong> 
                    <?php echo htmlentities($_SESSION['msg']); ?>
                    <?php $_SESSION['msg']=""; ?>
                </div>
            <?php } ?>
            <?php if($_SESSION['delmsg']!="") { ?>
                <div class="alert alert-success">
                    <strong>Succès :</strong> 
                    <?php echo htmlentities($_SESSION['delmsg']); ?>
                    <?php $_SESSION['delmsg']=""; ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Liste des Catégories
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Statut</th>
                                            <th>Créée le</th>
                                            <th>Mise à jour le</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * FROM tblcategory ORDER BY id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->CategoryName);?></td>
                                                    <td>
                                                        <?php 
                                                        if($result->Status==1) {
                                                            echo '<span class="text-success">Active</span>';
                                                        } else {
                                                            echo '<span class="text-danger">Inactive</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo htmlentities($result->CreationDate);?></td>
                                                    <td><?php echo htmlentities($result->UpdationDate);?></td>
                                                    <td>
                                                        <a href="edit-category.php?catid=<?php echo htmlentities($result->id);?>">
                                                            <button class="btn btn-primary btn-sm">Éditer</button>
                                                        </a>
                                                        <a href="manage-categories.php?del=<?php echo htmlentities($result->id);?>" 
                                                           onclick="return confirm('Confirmer la désactivation ?');">
                                                            <button class="btn btn-danger btn-sm">Supprimer</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php 
                                                $cnt++;
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>