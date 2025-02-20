<?php
session_start();

include('includes/config.php');

// Vérifier si l'administrateur est connecté
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Récupérer l'ID du livre à éditer
    if (isset($_GET['id'])) {
        $bookid = intval($_GET['id']);

        // Traitement de la mise à jour du livre
        if (isset($_POST['update'])) {
            $bookname = $_POST['bookname'];
            $category = $_POST['category'];
            $author = $_POST['author'];
            $isbn = $_POST['isbn'];
            $price = $_POST['price'];

            // Vérifier si l'ISBN existe déjà pour un autre livre
            $sql = "SELECT id FROM tblbooks WHERE ISBNNumber=:isbn AND id != :bookid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
            $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                echo "<script>alert('ISBN déjà utilisé par un autre livre');</script>";
            } else {
                $sql = "UPDATE tblbooks SET BookName=:bookname,CatId=:category,AuthorId=:author,
                        ISBNNumber=:isbn,BookPrice=:price WHERE id=:bookid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
                $query->bindParam(':category', $category, PDO::PARAM_STR);
                $query->bindParam(':author', $author, PDO::PARAM_STR);
                $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
                $query->bindParam(':price', $price, PDO::PARAM_STR);
                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query->execute();

                echo "<script>alert('Livre mis à jour avec succès');</script>";
                echo "<script>window.location.href='manage-books.php'</script>";
            }
        }

        // Récupérer les informations actuelles du livre
        $sql = "SELECT tblbooks.BookName, tblbooks.CatId, tblbooks.AuthorId, 
                tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid
                FROM tblbooks WHERE tblbooks.id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
?>

        <!DOCTYPE html>
        <html lang="FR">

        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

            <title>Gestion de bibliothèque en ligne | Édition livre</title>
            <!-- BOOTSTRAP CORE STYLE  -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
            <!-- FONT AWESOME STYLE  -->
            <link href="assets/css/font-awesome.css" rel="stylesheet" />
            <!-- CUSTOM STYLE  -->
            <link href="assets/css/style.css" rel="stylesheet" />
        </head>

        <body>
            <!------MENU SECTION START-->
            <?php include('includes/header.php'); ?>

            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="header-line">Éditer un livre</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Informations du livre
                                </div>
                                <div class="panel-body">
                                    <form role="form" method="post">
                                        <div class="form-group">
                                            <label>Titre du livre<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="bookname"
                                                value="<?php echo htmlentities($result->BookName); ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Catégorie<span style="color:red;">*</span></label>
                                            <select class="form-control" name="category" required>
                                                <option value="">Sélectionner une catégorie</option>
                                                <?php
                                                $sql = "SELECT * FROM tblcategory ORDER BY CategoryName ASC";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($categories as $category) { ?>
                                                        <option value="<?php echo htmlentities($category->id); ?>"
                                                            <?php if ($result->CatId == $category->id) echo 'selected="selected"'; ?>>
                                                            <?php echo htmlentities($category->CategoryName); ?>
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
                                                $sql = "SELECT * FROM tblauthors ORDER BY AuthorName ASC";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $authors = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($authors as $author) { ?>
                                                        <option value="<?php echo htmlentities($author->id); ?>"
                                                            <?php if ($result->AuthorId == $author->id) echo 'selected="selected"'; ?>>
                                                            <?php echo htmlentities($author->AuthorName); ?>
                                                        </option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>ISBN<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="isbn"
                                                value="<?php echo htmlentities($result->ISBNNumber); ?>" required />
                                        </div>

                                        <div class="form-group">
                                            <label>Prix<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="price"
                                                value="<?php echo htmlentities($result->BookPrice); ?>" required />
                                        </div>

                                        <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
            <!-- FOOTER SECTION END-->
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        </body>

        </html>
<?php
    }
}
?>