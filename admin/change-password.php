<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
	// On le redirige vers la page de login
	header('location:index.php');
} else {
	// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
	// Si le formulaire a bien ete soumis
	if (isset($_POST['change'])) {
		// On recupere le mot de passe courant
		$password = md5($_POST['password']);
		// On recupere le nouveau mot de passe
		$newpassword = md5($_POST['newpassword']);
		// On recupere le nom de l'utilisateur stocké dans $_SESSION
		$username = $_SESSION['alogin'];

		// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
		$sql = "SELECT id FROM admin WHERE UserName=:username and Password=:password";
		$query = $dbh->prepare($sql);
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		// On execute la requete
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_OBJ);

		// Si on trouve un resultat
		if ($query->rowCount() > 0) {
			// On prepare la requete de mise a jour du nouveau mot de passe de cet id
			$sql = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
			$query = $dbh->prepare($sql);
			$query->bindParam(':username', $username, PDO::PARAM_STR);
			$query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
			$query->execute();
			// On stocke un message de succès de l'operation
			$msg = "Mot de passe modifié avec succès";
			// On purge le message d'erreur
			$error = "";
		} else {
			// Sinon on a trouve personne	
			// On stocke un message d'erreur
			$error = "Mot de passe actuel incorrect";
			$msg = "";
		}
	} else {
		// Sinon le formulaire n'a pas encore ete soumis
		// On initialise le message de succes et le message d'erreur (chaines vides)
		$error = "";
		$msg = "";
	}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion bibliotheque en ligne | Changement mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
	<style>
		.errorWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #fff;
			border-left: 4px solid #dd3d36;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
		.succWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #fff;
			border-left: 4px solid #5cb85c;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
	</style>
</head>

<script type="text/javascript">
	// On cree une fonction JS valid() qui renvoie
	function valid() {
		// true si les mots de passe sont identiques
		if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
			alert("Les mots de passe ne correspondent pas!");
			return false;
		}
		// false sinon
		return true;
	}
</script>

<body>
	<?php include('includes/header.php');?>
	<div class="content-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4 class="header-line">Changer de mot de passe</h4>
				</div>
			</div>
			<?php if($error) { ?>
				<div class="errorWrap"><strong>ERREUR</strong>: <?php echo htmlentities($error); ?></div>
			<?php } else if($msg) { ?>
				<div class="succWrap"><strong>SUCCÈS</strong>: <?php echo htmlentities($msg); ?></div>
			<?php } ?>
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
					<div class="panel panel-info">
						<div class="panel-heading">
							Formulaire de changement de mot de passe
						</div>
						<div class="panel-body">
							<form role="form" method="post" onSubmit="return valid();" name="chngpwd">
								<div class="form-group">
									<label>Mot de passe actuel</label>
									<input class="form-control" type="password" name="password" required autocomplete="off" />
								</div>

								<div class="form-group">
									<label>Nouveau mot de passe</label>
									<input class="form-control" type="password" name="newpassword" required autocomplete="off" />
								</div>

								<div class="form-group">
									<label>Confirmer le mot de passe</label>
									<input class="form-control" type="password" name="confirmpassword" required autocomplete="off" />
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