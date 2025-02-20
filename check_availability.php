<?php

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");

// Vérifier si le paramètre mail existe
if (!isset($_GET['mail']) || empty($_GET['mail'])) {
	echo json_encode(array("rep" => "error", "message" => "Email non fourni"));
	exit;
}
// On recupere dans $_GET l email soumis par l'utilisateur
$inputEmail = strip_tags($_GET['mail']);
// On verifie que l'email est un email valide (fonction php filter_var)
if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
	// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
	$sql = "SELECT EmailId FROM tblreaders WHERE EmailId=:email";
	$query = $dbh->prepare($sql);
	$query->bindParam(':email', $inputEmail, PDO::PARAM_STR);

	// On execute la requete et on stocke le resultat de recherche
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);

	// Vérification du résultat
	// Si le résultat de recherche est vide
	if (empty($result)) {
		// On echo un objet JSON '{"rep":"ok"}'
		echo json_encode(array("rep" => "ok"));
		// Sinon
	} else {
		// On echo un objet JSON '{"rep":"nok"}'
		echo json_encode(array("rep" => "nok"));
	}
}
