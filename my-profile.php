<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['login']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de profil
    if (isset($_POST['update'])) {
        // On recupere l'id du lecteur (cle secondaire)
        $sid = $_SESSION['rdid'];
        // On recupere le nom complet du lecteur
        $fullname = $_POST['fullname'];
        // On recupere le numero de portable
        $mobileno = $_POST['mobileno'];

        // On update la table tblreaders avec ces valeurs
        $sql = "UPDATE tblreaders SET FullName=:fullname, MobileNumber=:mobileno WHERE ReaderId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->execute();

        // On informe l'utilisateur du resultat de l'operation
        echo "<script>alert('Profil mis à jour avec succès')</script>";
    }

    // On souhaite voir la fiche du lecteur courant.
    // On recupere l'id de session dans $_SESSION
    $sid = $_SESSION['rdid'];
    
    // On prepare la requete permettant d'obtenir le profil du lecteur
    $sql = "SELECT ReaderId, FullName, EmailId, MobileNumber, RegDate, UpdateDate, Status FROM tblreaders WHERE ReaderId=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
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
    
    <!--On affiche le titre de la page : EDITION DU PROFIL-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">EDITION DU PROFIL</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 offset-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Identifiant lecteur</label>
                                    <input class="form-control" type="text" name="studentid" value="<?php echo htmlentities($result->ReaderId); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Date d'enregistrement</label>
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->RegDate); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Dernière mise à jour</label>
                                    <input class="form-control" type="text" value="<?php echo htmlentities($result->UpdateDate); ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <input class="form-control" type="text" value="<?php echo $result->Status == 1 ? 'Actif' : 'Bloqué'; ?>" readonly />
                                </div>

                                <div class="form-group">
                                    <label>Nom complet</label>
                                    <input class="form-control" type="text" name="fullname" value="<?php echo htmlentities($result->FullName); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Numéro de portable</label>
                                    <input class="form-control" type="text" name="mobileno" value="<?php echo htmlentities($result->MobileNumber); ?>" required />
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" value="<?php echo htmlentities($result->EmailId); ?>" readonly />
                                </div>

                                <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
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