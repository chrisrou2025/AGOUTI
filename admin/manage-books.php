<?php
session_start();
include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Traitement de la suppression d'un livre
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        
        try {
            $sql = "DELETE FROM tblbooks WHERE id=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo "<script>alert('Livre supprimé avec succès');</script>";
            echo "<script>window.location.href='manage-books.php'</script>";
        } catch(PDOException $e) {
            echo "<script>alert('Erreur lors de la suppression');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Gestion des Livres</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Liste des Livres
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Auteur</th>
                                        <th>ISBN</th>
                                        <th>Prix</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Récupérer tous les livres avec leurs catégories et auteurs
                                    $sql = "SELECT tblbooks.id, tblbooks.BookName, tblcategory.CategoryName, 
                                                 tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice 
                                           FROM tblbooks 
                                           JOIN tblcategory ON tblcategory.id=tblbooks.CatId 
                                           JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId 
                                           ORDER BY tblbooks.id DESC";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;

                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt);?></td>
                                                <td><?php echo htmlentities($result->BookName);?></td>
                                                <td><?php echo htmlentities($result->CategoryName);?></td>
                                                <td><?php echo htmlentities($result->AuthorName);?></td>
                                                <td><?php echo htmlentities($result->ISBNNumber);?></td>
                                                <td><?php echo htmlentities($result->BookPrice);?></td>
                                                <td>
                                                    <a href="edit-book.php?id=<?php echo htmlentities($result->id);?>">
                                                        <button class="btn btn-primary btn-sm">Éditer</button>
                                                    </a>
                                                    <a href="manage-books.php?del=<?php echo htmlentities($result->id);?>" 
                                                       onclick="return confirm('Confirmer la suppression ?');">
                                                        <button class="btn btn-danger btn-sm">Supprimer</button>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php 
                                            $cnt++;
                                        }
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
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
