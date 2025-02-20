<?php
// On d�marre ou on r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
	$_SESSION['login'] = '';
}

if (isset($_POST['login'])) {
	// On vient ici après la soumission du formulaire de login (plus bas dans ce fichier
	// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
	// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas
	if ($_POST["vercode"] != $_SESSION["vercode"] or $_SESSION["vercode"] == '' or $_POST['vercode'] == '') {
		// Le code est incorrect
		echo "<script>alert('Code de verification incorrect');</script>";
	} else {
		// Le code est correct, on peut continuer
		// On récupère le mail de l'utilisateur saisi dans le formulaire
		$email = strip_tags($_POST['emailid']);
		// On récupère le mot de passe saisi par l'utilisateur
		$password = strip_tags($_POST['password']);
		// On construit la requete SQL pour récupérer l'id, le readerId et l'email du lecteur � partir des deux variables ci-dessus
		// dans la table tblreaders
		$sql = "SELECT EmailId,Password,ReaderId,Status FROM tblreaders WHERE EmailId=:email";

		// On execute la requete
		$query = $dbh->prepare($sql);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->execute();
		// On stocke le résultat de recherche dans une variable $result
		$result = $query->fetch(PDO::FETCH_OBJ);

		// Si il y a qqchose dans result
		// et si le mot de passe saisi est correct
		if (!empty($result) && password_verify($password, $result->Password)) {
			// On stocke l'identifiant du lecteur (ReaderId dans $_SESSION)
			$_SESSION['rdid'] = $result->ReaderId;

			if ($result->Status == 1) {
				// Si le statut du lecteur est actif ( égal à 1)
				// On stocke l'email du lecteur dans $_SESSION
				$_SESSION['login'] = $email;
				// l'utilisateur est redirig� vers dashboard.php
				//echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
				header('location:dashboard.php');
			} else {
				// Sinon le compte du lecteur a �t� bloqu�
				echo "<script>alert('Votre compte a été bloqué. Contactez votre administrateur');</script>";
			}
		} else {
			// Sinon la connexion n'est pas valide
			echo "<script>alert('Mot de passe invalide');</script>";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion de librairie en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<!--link href="assets/css/bootstrap.css" rel="stylesheet" /-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
	<!--On inclue ici le menu de navigation includes/header.php-->
	<?php include('includes/header.php'); ?>

	<!-- Titre de la page (LOGIN UTILISATEUR) -->
	<div class="content-wrapper">
		<div class="container">
			<div class="row">
				<!--pad-botm-->
				<div class="col-md-12">
					<h4 class="header-line">LOGIN LECTEUR</h4>
				</div>
			</div>

			<!--On ins�re le formulaire de login-->
			<!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
			<div class="row">
				<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
					<div class="panel panel-info">
						<div class="panel-body">
							<form role="form" method="post" action="">
								<div class="form-group">
									<label>Entrez votre email</label>
									<input class="form-control" type="text" name="emailid" required autocomplete="off" />
								</div>
								<div class="form-group">
									<label>Mot de passe</label>
									<input class="form-control" type="password" name="password" required autocomplete="off" />
									<p class="help-block">
										<a href="user-forgot-password.php">Mot de passe oublié ?</a>
									</p>
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

								<button type="submit" name="login" class="btn btn-info">LOGIN </button>&nbsp;&nbsp;&nbsp;<a href="signup.php">Je n'ai pas de compte</a>
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
	<!--script src="assets/js/custom.js"></script -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>