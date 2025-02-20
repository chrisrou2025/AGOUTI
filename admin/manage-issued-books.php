<?php
session_start();

include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Pour déboguer - Afficher le nombre de résultats
    try {
        $check = "SELECT COUNT(*) as count FROM tblissuedbookdetails";
        $checkQuery = $dbh->prepare($check);
        $checkQuery->execute();
        $count = $checkQuery->fetch(PDO::FETCH_OBJ);
        echo "<script>console.log('Nombre total de sorties : " . $count->count . "');</script>";
    } catch(PDOException $e) {
        echo "<script>console.log('Erreur lors du comptage : " . $e->getMessage() . "');</script>";
    }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Gestion des Sorties</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Pour déboguer - Afficher les erreurs PDO -->
                    <?php
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Liste des Livres Empruntés
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Lecteur</th>
                                            <th>Titre du Livre</th>
                                            <th>ISBN</th>
                                            <th>Date de Sortie</th>
                                            <th>Date de Retour</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    try {
                                        // Récupérer la liste des emprunts
                                        $sql = "SELECT tblissuedbookdetails.id as id, 
                                                    tblreaders.FullName, 
                                                    tblbooks.BookName, 
                                                    tblbooks.ISBNNumber, 
                                                    tblissuedbookdetails.IssuesDate, 
                                                    tblissuedbookdetails.ReturnDate
                                            FROM tblissuedbookdetails 
                                            JOIN tblreaders ON tblreaders.ReaderId = tblissuedbookdetails.ReaderId 
                                            JOIN tblbooks ON tblbooks.ISBNNumber = tblissuedbookdetails.BookId 
                                            ORDER BY tblissuedbookdetails.id DESC";
                                        
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        // Pour déboguer - Afficher le nombre de résultats de la requête
                                        echo "<div class='alert alert-info'>Nombre de sorties trouvées : " . $query->rowCount() . "</div>";

                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result->FullName);?></td>
                                                    <td><?php echo htmlentities($result->BookName);?></td>
                                                    <td><?php echo htmlentities($result->ISBNNumber);?></td>
                                                    <td><?php echo htmlentities($result->IssuesDate);?></td>
                                                    <td>
                                                        <?php 
                                                        if($result->ReturnDate == NULL) {
                                                            echo "Non retourné";
                                                        } else {
                                                            echo htmlentities($result->ReturnDate);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit-issue-book.php?id=<?php echo htmlentities($result->id);?>">
                                                            <button class="btn btn-primary">Éditer</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php 
                                            $cnt++;
                                            }
                                        }
                                    } catch(PDOException $e) {
                                        echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
<?php } ?>

