<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est pas connecté (la variable de session login est vide)
if (strlen($_SESSION['login']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Si le bouton de suppression a été cliqué ($_GET['del'] existe)
    if (isset($_GET['del'])) {
        // On récupère l'identifiant du livre
        $id = $_GET['del'];
        
        // On supprime le livre en base avec une requête DELETE
        $sql = "DELETE FROM tblissuedbookdetails WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        
        // On redirige l'utilisateur vers issued-books.php
        header('location:issued-books.php');
    }
    
    // On récupère l'identifiant du lecteur dans la session
    $sid = $_SESSION['rdid'];
    
    // Requête SQL pour récupérer les informations des livres empruntés par le lecteur
    $sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, tblissuedbookdetails.fine 
            FROM tblissuedbookdetails 
            JOIN tblbooks ON tblbooks.id = tblissuedbookdetails.BookId 
            WHERE tblissuedbookdetails.ReaderId=:sid 
            ORDER BY tblissuedbookdetails.id DESC";
            
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Livres empruntés</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On insère ici le menu de navigation -->
    <?php include('includes/header.php'); ?>
    
    <!-- On affiche le titre de la page -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">LIVRES EMPRUNTÉS</h4>
                </div>
            </div>
            
            <!-- On affiche la liste des livres empruntés -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titre</th>
                                            <th>ISBN</th>
                                            <th>Date d'emprunt</th>
                                            <th>Date de retour</th>
                                            <th>Amende</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Compteur pour numéroter les lignes
                                        $cnt = 1;
                                        // On vérifie qu'il y a des résultats
                                        if($query->rowCount() > 0) {
                                            // Pour chaque livre emprunté
                                            foreach($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo $cnt;?></td>
                                                    <td><?php echo htmlentities($result->BookName);?></td>
                                                    <td><?php echo htmlentities($result->ISBNNumber);?></td>
                                                    <td><?php echo htmlentities($result->IssuesDate);?></td>
                                                    <td>
                                                        <?php 
                                                        if($result->ReturnDate == "") {
                                                            echo "Non retourné";
                                                        } else {
                                                            echo htmlentities($result->ReturnDate);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $result->fine ? htmlentities($result->fine) : "0";?> €</td>
                                                    <td>
                                                        <a href="issued-books.php?del=<?php echo htmlentities($result->rid);?>" 
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?');" >
                                                           <button class="btn btn-danger btn-sm">Supprimer</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $cnt++; }
                                        } else { ?>
                                            <tr>
                                                <td colspan="7">Aucun livre emprunté trouvé</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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