<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page d'accueil
    header('location:index.php');
} else {
    // Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        // et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur
        $sql = "UPDATE tblreaders SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }

    // Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        // et on met à jour le statut (1) dans la table tblreaders pour cet identifiant de lecteur
        $sql = "UPDATE tblreaders SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }

    // Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $status = 2;
        // et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur
        $sql = "UPDATE tblreaders SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
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
                    <h4 class="header-line">Gestion du Registre des lecteurs</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Registre des Lecteurs
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID Lecteur</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Date d'inscription</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // On récupère tous les lecteurs dans la base de données
                                        $sql = "SELECT * FROM tblreaders ORDER BY id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->ReaderId);?></td>
                                                    <td><?php echo htmlentities($result->FullName);?></td>
                                                    <td><?php echo htmlentities($result->EmailId);?></td>
                                                    <td><?php echo htmlentities($result->RegDate);?></td>
                                                    <td>
                                                        <?php 
                                                        if($result->Status == 1) {
                                                            echo '<span class="text-success">Actif</span>';
                                                        } else if($result->Status == 0) {
                                                            echo '<span class="text-danger">Bloqué</span>';
                                                        } else {
                                                            echo '<span class="text-danger">Supprimé</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if($result->Status == 1) { ?>
                                                            <a href="reg-readers.php?inid=<?php echo htmlentities($result->id);?>" 
                                                               onclick="return confirm('Bloquer ce lecteur ?');">
                                                               <button class="btn btn-warning btn-sm text-white">Inactif</button>
                                                            </a>
                                                            <a href="reg-readers.php?del=<?php echo htmlentities($result->id);?>" 
                                                               onclick="return confirm('Supprimer ce lecteur ?');">
                                                                <button class="btn btn-danger btn-sm">Supprimer</button>
                                                            </a>
                                                        <?php } else if($result->Status == 0) { ?>
                                                            <a href="reg-readers.php?id=<?php echo htmlentities($result->id);?>" 
                                                               onclick="return confirm('Activer ce lecteur ?');">
                                                                <button class="btn btn-success btn-sm">Actif</button>
                                                            </a>
                                                            <a href="reg-readers.php?del=<?php echo htmlentities($result->id);?>" 
                                                               onclick="return confirm('Supprimer ce lecteur ?');">
                                                                <button class="btn btn-danger btn-sm">Supprimer</button>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php 
                                                $cnt++;
                                            }
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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