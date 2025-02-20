<?php 
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
require_once("includes/config.php");

/* On recupere le numero ISBN du livre*/
if(!empty($_POST["bookid"])) {
    $bookid = filter_var($_POST["bookid"], FILTER_SANITIZE_STRING);
    
    // On prepare la requete de recherche du livre correspondant
    $sql = "SELECT BookName, IssuedStatus FROM tblbooks WHERE ISBNNumber=:bookid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    
    // On execute la requete
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Si un resultat est trouve
    if($query->rowCount() > 0) {
        if($result->IssuedStatus == 0) {
            // On affiche le nom du livre
            echo htmlentities($result->BookName);
            // On active le bouton de soumission du formulaire
            echo "<script>document.getElementById('submit').disabled = false;</script>";
        } else {
            // Si le livre est déjà emprunté
            echo "<span style='color:red'>Livre déjà emprunté</span>";
            // On desactive le bouton de soumission du formulaire
            echo "<script>document.getElementById('submit').disabled = true;</script>";
        }
    } else {
        // On affiche que "ISBN est non valide"
        echo "<span style='color:red'>ISBN non valide</span>";
        // On desactive le bouton de soumission du formulaire
        echo "<script>document.getElementById('submit').disabled = true;</script>";
    }
}
?>
