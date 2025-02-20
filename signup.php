<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

//error_log(print_r($_POST, 1));
// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
//$_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if (isset($_POST['signup'])) {
  //error_log(print_r($_SESSION, 1));

  if (isset($_POST['vercode']) && ($_POST['vercode'] != $_SESSION['vercode'])) {
    echo "<script>alert('code de vérification incorrect')";
  } else {

    // Lire le dernier ID
    $ressourceLue = file('readerid.txt');
    $lastId = intval($ressourceLue[0]);

    // Incrémenter et formater
    $nextId = $lastId + 1;
    $ressourceIncr = 'SID' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    // Écrire le nouveau numéro
    file_put_contents('readerid.txt', $nextId);

    // On récupère le nom saisi par le lecteur
    $userName = $_POST['userName'];
    // On récupère l'email
    $userEmail = $_POST['email'];
    // On récupère le numéro de portable
    $userMobile = $_POST['mobileNumber'];
    // On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction passwword_hash)
    $userPassword = password_hash($_POST['password1'], PASSWORD_DEFAULT);
    // On fixe le statut du lecteur à 1 par défaut (actif)
    $status = 1;

    // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders

    $sql = "INSERT INTO tblreaders(ReaderId, FullName, EmailId, MobileNumber, Password, Status) VALUES(:readerId, :fullName, :email, :mobileNumber, :password, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId', $ressourceIncr, PDO::PARAM_STR);
    $query->bindParam(':fullName', $userName, PDO::PARAM_STR);
    $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $query->bindParam(':mobileNumber', $userMobile, PDO::PARAM_STR);
    $query->bindParam(':password', $userPassword, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->execute();

    // On récupère le dernier id inséré en bd (fonction lastInsertId)
    $lastId = $dbh->lastInsertId();
    // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $ressourceLue[0]), sinon on affiche qu'il y a eu un problème
    if (isset($lastId)) {
      echo "<script>alert('L\'opération s\'est bien déroulée " . $ressourceIncr . "')</script>";
    } else {
      echo "<script>alert('L'opération a échouée, il y eu un problème')</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
  <title>Gestion de bibliotheque en ligne | Signup</title>
  <!-- BOOTSTRAP CORE STYLE  -->
  <!--link href="assets/css/bootstrap.css" rel="stylesheet" /-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- FONT AWESOME STYLE  -->
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <!-- CUSTOM STYLE  -->
  <link href="assets/css/style.css" rel="stylesheet" />

  <script type="text/javascript">
    // On cree une fonction valid() sans paramètre qui renvoie 
    // TRUE si les mots de passe saisis dans le formulaire sont identiques
    // FALSE sinon

    function valid() {
      let password1 = document.getElementById("createdPassword").value;
      let password2 = document.getElementById("confirmedPassword").value;

      if (password1 !== password2) {
        alert("Les mots de passe ne correspondent pas!");
        return false;
      }
      return true;
    };

    // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
    // Cette fonction effectue un appel AJAX vers check_availability.php

    function checkAvailability(mail) {
      // Vérifier si mail n'est pas vide
      if (!mail) {
        return; // Ne rien faire si le champ est vide
      }

      // Encoder l'email pour éviter les problèmes avec les caractères spéciaux
      const encodedMail = encodeURIComponent(mail);

      // Utiliser un appel fetch vers check_avaiability.php
      fetch(`check_availability.php?mail=${mail}`)
        .then(response => response.json())
        .then(data => {
          if (data.rep === "nok") {
            alert("Cet email est déjà utilisé !");
            document.getElementById("email").value = "";
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          alert("Une erreur est survenue lors de la vérification de l'email");
        });
    }
  </script>
</head>

<body>
  <!-- On inclue le fichier header.php qui contient le menu de navigation-->
  <?php include('includes/header.php'); ?>

  <!-- Titre de la page (LOGIN UTILISATEUR) -->
  <div class="content-wrapper">
    <div class="container">
      <div class="row">
        <!--pad-botm-->
        <div class="col-md-12">
          <h4 class="header-line">CREER UN COMPTE</h4>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
          <div class="panel panel-info">
            <div class="panel-body">
              <!--On créé le formulaire de creation de compte-->
              <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
              <form action="signup.php" method="POST" onSubmit="return valid()">
                <div class="form-group">
                  <label>Entrez votre nom et prénom</label>
                  <input class="form-control" type="text" name="userName" required>
                </div>
                <div class="form-group">
                  <label>Portable</label>
                  <input class="form-control" type="text" name="mobileNumber" required>
                </div>
                <div class="form-group">
                  <label>Entrez votre email</label>
                  <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
                  <input class="form-control" type="text" name="email" id="email" required onblur="checkAvailability(this.value)">
                </div>

                <div class="form-group">
                  <label>Saisissez un mot de passe</label>
                  <input class="form-control" id="createdPassword" type="password" name="password1" required><br>
                  <label>Confirmez votre mot de passe</label>
                  <input class="form-control" id="confirmedPassword" type="password" name="password2" required>
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
                    <img src="captcha.php" style="height: 38px;" /> <!-- Hauteur ajustée -->
                  </div>
                </div>

                <button type="submit" name="signup" class="btn btn-info" id="submit">Enregistrer</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!---LOGIN PABNEL END-->
    </div>
  </div>
  <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php'); ?>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>