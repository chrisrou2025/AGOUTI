<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Après la soumission du formulaire de login ($_POST['change'] existe
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)

if (isset($_POST['change'])) {
    // Vérification du captcha
    if ($_POST["vercode"] != $_SESSION["vercode"] || empty($_SESSION["vercode"]) || empty($_POST["vercode"])) {
     // Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect');</script>";
    } else {
          // Sinon on continue
          // on recupere l'email et le numero de portable saisi par l'utilisateur
          // et le nouveau mot de passe que l'on encode (fonction password_hash)
        // Récupération des données du formulaire
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $newpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
        // Recherche de l'utilisateur dans la base de données
        $sql = "SELECT EmailId, MobileNumber FROM tblreaders WHERE EmailId=:email AND MobileNumber=:mobile";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result) {
          // Si le resultat de recherche n'est pas vide
          // On met a jour la table tblreaders avec le nouveau mot de passe
            // Mise à jour du mot de passe
            $sql = "UPDATE tblreaders SET Password=:newpassword WHERE EmailId=:email AND MobileNumber=:mobile";
            $query = $dbh->prepare($sql);
            $query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            
            // On informe l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
            if ($query->execute()) {
                echo "<script>alert('Mot de passe modifié avec succès');</script>";
            } else {
                echo "<script>alert('Erreur lors de la modification du mot de passe');</script>";
            }
        } else {
            echo "<script>alert('Email ou numéro de mobile incorrect');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

<script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
    function valid() {
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmpassword").value;
        
        if (password !== confirmPassword) {
            alert("Les mots de passe ne correspondent pas!");
            return false;
        }
        return true;
    }
</script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">RÉCUPÉRATION MOT DE PASSE</h4>
            </div>
        </div>
     <!--On insere le formulaire de recuperation-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 offset-md-3">
                <div class="panel panel-info">
                    <div class="panel-body">
                         <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->
                        <form role="form" method="post" onSubmit="return valid();">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" required />
                            </div>
                            <div class="form-group">
                                <label>Numéro de mobile</label>
                                <input class="form-control" type="text" name="mobile" required />
                            </div>
                            <div class="form-group">
                                <label>Nouveau mot de passe</label>
                                <input class="form-control" type="password" name="password" id="password" required />
                            </div>
                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <input class="form-control" type="password" name="confirmpassword" id="confirmpassword" required />
                            </div>
                            <div class="form-group">
                                <label>Code de Verification : </label>
                            <div class="d-flex align-items-center"> <!-- Nouveau conteneur flex -->
                                <input type="text" 
                                    class="form-control" 
                                    name="vercode" 
                                    maxlength="5" 
                                    autocomplete="off" 
                                    required 
               style="width: 150px; margin-right: 10px;" /> <!-- Largeur fixe et marge -->
        <img src="captcha.php" style="height: 38px;"/> <!-- Hauteur ajustée -->
    </div>
</div>
                            <button type="submit" name="change" class="btn btn-info">Envoyer</button>
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