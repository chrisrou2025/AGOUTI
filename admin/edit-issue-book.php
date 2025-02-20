<?php
session_start();

include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Traitement de la modification d'emprunt
    if (isset($_POST['update'])) {
        $id = intval($_GET['id']);
        $returnDate = $_POST['returnDate'];
        
        try {
            $sql = "UPDATE tblissuedbookdetails SET ReturnDate=:returnDate WHERE id=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':returnDate', $returnDate, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            
            if ($query->execute()) {
                // Si une date de retour est définie, on met à jour le statut du livre
                if (!empty($returnDate)) {
                    $sql = "UPDATE tblbooks SET IssuedStatus=0 
                           WHERE ISBNNumber IN (SELECT BookId FROM tblissuedbookdetails WHERE id=:id)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                    $query->execute();
                }
                echo "<script>alert('Informations mises à jour avec succès');</script>";
                echo "<script>window.location.href='manage-issued-books.php'</script>";
            }
        } catch(PDOException $e) {
            echo "<script>alert('Une erreur est survenue');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Modifier Sortie</title>
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
<!-- MENU SECTION END-->

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Modifier Sortie</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Informations de Sortie
                        </div>
                        <div class="panel-body">
                            <?php 
                            $id = intval($_GET['id']);
                            $sql = "SELECT tblissuedbookdetails.id as rid, tblreaders.FullName, tblbooks.BookName, 
                                          tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate 
                                   FROM tblissuedbookdetails 
                                   JOIN tblreaders ON tblreaders.ReaderId=tblissuedbookdetails.StudentID 
                                   JOIN tblbooks ON tblbooks.ISBNNumber=tblissuedbookdetails.BookId 
                                   WHERE tblissuedbookdetails.id=:id";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':id', $id, PDO::PARAM_STR);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            ?>

                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>ID Sortie :</label>
                                    <?php echo htmlentities($result->rid);?>
                                </div>

                                <div class="form-group">
                                    <label>Lecteur :</label>
                                    <?php echo htmlentities($result->FullName);?>
                                </div>

                                <div class="form-group">
                                    <label>Livre :</label>
                                    <?php echo htmlentities($result->BookName);?>
                                </div>

                                <div class="form-group">
                                    <label>ISBN :</label>
                                    <?php echo htmlentities($result->ISBNNumber);?>
                                </div>

                                <div class="form-group">
                                    <label>Date d'emprunt :</label>
                                    <?php echo htmlentities($result->IssuesDate);?>
                                </div>

                                <div class="form-group">
                                    <label>Date de retour :</label>
                                    <input class="form-control" type="date" name="returnDate" 
                                           value="<?php echo htmlentities($result->ReturnDate);?>" />
                                </div>

                                <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
