<?php
session_start();
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = "";
}
include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
} else {
    // Traitement de l'ajout d'un livre
    if (isset($_POST['add'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $price = $_POST['price'];

        // Vérifier si l'ISBN existe déjà
        $sql = "SELECT ISBNNumber FROM tblbooks WHERE ISBNNumber=:isbn";
        $query = $dbh->prepare($sql);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo "<script>alert('ISBN déjà enregistré');</script>";
        } else {
            $sql = "INSERT INTO tblbooks(BookName,CatId,AuthorId,ISBNNumber,BookPrice) 
                    VALUES(:bookname,:category,:author,:isbn,:price)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
            $query->bindParam(':author', $author, PDO::PARAM_STR);
            $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
            $query->bindParam(':price', $price, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                echo "<script>alert('Livre ajouté avec succès');</script>";
                echo "<script>window.location.href='manage-books.php'</script>";
            } else {
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

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
                <h4 class="header-line">Ajouter un livre</h4>
            </div>
        </div>

        <?php if($_SESSION['error']!="") { ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> 
                <?php echo htmlentities($_SESSION['error']); ?>
                <?php $_SESSION['error']=""; ?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        Informations Livre
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group">
                                <label>Titre du livre<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="bookname" required />
                            </div>

                            <div class="form-group">
                                <label>Catégorie<span style="color:red;">*</span></label>
                                <select class="form-control" name="category" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php 
                                    $sql = "SELECT * FROM tblcategory WHERE Status=1 ORDER BY CategoryName";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>
                                            <option value="<?php echo htmlentities($result->id);?>">
                                                <?php echo htmlentities($result->CategoryName);?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Auteur<span style="color:red;">*</span></label>
                                <select class="form-control" name="author" required>
                                    <option value="">Sélectionner un auteur</option>
                                    <?php 
                                    $sql = "SELECT * FROM tblauthors WHERE Status=1 ORDER BY AuthorName";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>
                                            <option value="<?php echo htmlentities($result->id);?>">
                                                <?php echo htmlentities($result->AuthorName);?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>ISBN<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="isbn" required />
                            </div>

                            <div class="form-group">
                                <label>Prix<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="price" required />
                            </div>

                            <button type="submit" name="add" class="btn btn-info">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>