<?php
session_start();

include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Traitement du formulaire de sortie
    if (isset($_POST['issue'])) {
        $readerid = htmlspecialchars($_POST['readerid'], ENT_QUOTES, 'UTF-8');
        $bookid = htmlspecialchars($_POST['bookid'], ENT_QUOTES, 'UTF-8');
        $isissued = 1;

        // Vérifier si le livre est déjà emprunté
        $sql = "SELECT * FROM tblbooks WHERE ISBNNumber=:bookid AND IssuedStatus=0";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        
        if ($query->rowCount() > 0) {
            try {
                $dbh->beginTransaction();

                // Insérer la sortie dans la table tblissuedbookdetails
                $sql = "INSERT INTO tblissuedbookdetails(StudentID, BookId, IssuesDate) VALUES(:readerid, :bookid, NOW())";
                $query = $dbh->prepare($sql);
                $query->bindParam(':readerid', $readerid, PDO::PARAM_STR);
                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query->execute();

                // Mettre à jour le statut du livre
                $sql2 = "UPDATE tblbooks SET IssuedStatus=:isissued WHERE ISBNNumber=:bookid";
                $query2 = $dbh->prepare($sql2);
                $query2->bindParam(':isissued', $isissued, PDO::PARAM_STR);
                $query2->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query2->execute();

                $dbh->commit();
                echo "<script>alert('Livre emprunté avec succès');</script>";
            } catch(PDOException $e) {
                $dbh->rollback();
                echo "<script>alert('Une erreur est survenue');</script>";
            }
        } else {
            echo "<script>alert('Ce livre n\'est pas disponible');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
        // Fonction pour récupérer le nom du lecteur à partir de son identifiant
        function getReader() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_reader.php",
                data: 'readerid=' + $("#readerid").val(),
                type: "POST",
                success: function(data) {
                    $("#get_reader_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {
                    $("#get_reader_name").html('Erreur de recherche');
                    $("#loaderIcon").hide();
                }
            });
        }

        // Fonction pour récupérer le titre du livre à partir de son ISBN
        function getBook() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_book.php",
                data: 'bookid=' + $("#bookid").val(),
                type: "POST",
                success: function(data) {
                    $("#get_book_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {
                    $("#get_book_name").html('Erreur de recherche');
                    $("#loaderIcon").hide();
                }
            });
        }
    </script>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">Ajouter une sortie</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Informations Sortie
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>ID Lecteur<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="readerid" id="readerid" onBlur="getReader()" required />
                                </div>
                                <div class="form-group">
                                    <span id="get_reader_name" style="font-size:16px;"></span> 
                                </div>

                                <div class="form-group">
                                    <label>ISBN Livre<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getBook()" required />
                                </div>
                                <div class="form-group">
                                    <span id="get_book_name" style="font-size:16px;"></span>
                                </div>

                                <button type="submit" name="issue" id="submit" class="btn btn-info">Ajouter la sortie</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>