<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['alogin']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// Apres la soumission du formulaire de login (plus bas dans ce fichier)
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas
if(isset($_POST['login'])) {
    // Vérification du captcha
    if ($_POST["vercode"] != $_SESSION["vercode"] || empty($_SESSION["vercode"]) || empty($_POST["vercode"])) {
        echo "<script>alert('Code de vérification incorrect');</script>";
    } else {
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        // Récupération des données du formulaire
        $username = $_POST['username'];

        // On recupere le mot de passe saisi par l'utilisateur et on le crypte
        $password = $_POST['password'];

        // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
        // depuis la table admin
        // Construction de la requête SQL
        $sql = "SELECT UserName,Password FROM admin WHERE UserName=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        // Si le resultat de recherche n'est pas vide 
        // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
        // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
        if($result && password_verify($password, $result->Password))  {
            // Création de la session administrateur
            $_SESSION['alogin'] = $_POST['username'];
            // Redirection vers le tableau de bord
            header('location:admin/dashboard.php');
        } else {
            // sinon le login est refuse. On le signal par une popup
            echo "<script>alert('Identifiants invalides');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
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

<!--On affiche le titre de la page-->
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">LOGIN ADMINISTRATION</h4>
            </div>
        </div>
        
<!--On affiche le formulaire de login-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 offset-md-3">
                <!-- <div class="panel panel-info">
                    <div class="panel-heading">
                        FORMULAIRE DE CONNEXION
                    </div> -->
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group">
                                <label>Nom d'utilisateur</label>
                                <input class="form-control" type="text" name="username" required />
                            </div>
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input class="form-control" type="password" name="password" required />
                            </div>

<!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
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
                            <button type="submit" name="login" class="btn btn-info">CONNEXION</button>
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