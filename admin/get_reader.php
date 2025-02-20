<?php 
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
require_once("includes/config.php");

/* On recupere le numero l'identifiant du lecteur SID---*/
if(!empty($_POST["readerid"])) {
    $sid = filter_var($_POST["readerid"], FILTER_SANITIZE_STRING);
    
    // On prepare la requete de recherche du lecteur correspondant
    $sql = "SELECT FullName, Status FROM tblreaders WHERE ReaderId=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    
    // On execute la requete
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Si un resultat est trouve
    if($query->rowCount() > 0) {
        if($result->Status == 1) {
            // On affiche le nom du lecteur
            echo htmlentities($result->FullName);
            // On active le bouton de soumission du formulaire
            echo "<script>document.getElementById('submit').disabled = false;</script>";
        } else {
            // Si le lecteur est bloque
            echo "<span style='color:red'>Lecteur bloqué</span>";
            // On desactive le bouton de soumission du formulaire
            echo "<script>document.getElementById('submit').disabled = true;</script>";
        }
    } else {
        // Si le lecteur n existe pas
        echo "<span style='color:red'>Lecteur non valide</span>";
        // On desactive le bouton de soumission du formulaire
        echo "<script>document.getElementById('submit').disabled = true;</script>";
    }
}
?>
