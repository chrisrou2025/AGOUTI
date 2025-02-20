<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
} else {
    // si le formulaire a ete envoye : $_POST['change'] existe
    if(isset($_POST['change'])) {
        // On récupère le mot de passe actuel saisi
        $currentPassword = $_POST['current-password'];
        // On récupère le nouveau mot de passe et on le crypte
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_SESSION['login'];
        
        // On vérifie d'abord le mot de passe actuel
        $sql = "SELECT Password FROM tblreaders WHERE EmailId=:email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0 && password_verify($currentPassword, $result->Password)) {
            // Le mot de passe actuel est correct, on peut mettre à jour
            $sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email";
            $query = $dbh->prepare($sql);
            $query->bindParam(':password', $newPassword, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            
            echo "<script>alert('Votre mot de passe a été modifié avec succès');</script>";
        } else {
            echo "<script>alert('Le mot de passe actuel est incorrect');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->
    <style>
        .error-message {
            color: #dc3545;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #dc3545;
            border-radius: 4px;
            background-color: #f8d7da;
        }
        .success-message {
            color: #28a745;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #28a745;
            border-radius: 4px;
            background-color: #d4edda;
        }
    </style>
</head>

<script type="text/javascript">
    /* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
    Cette fonction retourne un booleen*/
    function valid() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        
        if(password != confirmPassword) {
            alert("Les mots de passe ne correspondent pas!");
            return false;
        }
        return true;
    }
</script>

<body>
    <?php include('includes/header.php');?>
    
    <!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">CHANGER MON MOT DE PASSE</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 offset-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <!--On affiche le formulaire-->
                            <!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->
                            <form role="form" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Mot de passe actuel</label>
                                        <input class="form-control" type="password" name="current-password" id="current-password" required />
                                </div>

                                <div class="form-group">
                                     <label>Nouveau mot de passe</label>
                                         <input class="form-control" type="password" name="password" id="password" required />
                                 </div>

                            <div class="form-group">
                                    <label>Confirmer le mot de passe</label>
                                        <input class="form-control" type="password" name="confirm-password" id="confirm-password" required />
                            </div>

                                <button type="submit" name="change" class="btn btn-info">Changer</button>
                            </form>
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