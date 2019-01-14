

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="includes/icon.ico">

    <title>Projet BDD</title>

    <!-- Bootstrap core CSS -->
    <link href="includes/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="includes/stylesheet.css" rel="stylesheet">
    <link href="includes/cover.css" rel="stylesheet">
    <link href="includes/offcanvas.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<script src="includes/jquery-3.3.1.slim.min.js" ></script>
<script src="includes/popper.min.js" ></script>
<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand" >Projet BDD</div>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="remplir.php">Remplir</a></li>
                <li><a href="Requetes.php">Requêtes</a></li>

                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="display.php" id="navbardrop" data-toggle="dropdown">
                        Tables
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-center">
                        <a class="text-muted btn btn-dark text-center" href="display.php?bdd=Activites">Activites</a>
                        <div class="dropdown-divider"></div>
                        <a class="text-muted btn btn-dark text-center" href="display.php?bdd=Classes">Classes</a>
                        <div class="dropdown-divider"></div>
                        <a class="text-muted btn btn-dark text-center" href="display.php?bdd=Eleve">Eleve</a>
                        <div class="dropdown-divider"></div>
                        <a class="text-muted btn btn-dark text-center" href="display.php?bdd=Repartition">Repartition</a>
                    </div>
                </li>
            </ul>
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar -->

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">


        <?php
            if(isset($_GET['bdd'])) echo '<div class="jumbotron"><h2 class="cover-heading">Table:'.$_GET['bdd'].'</h2></div>';
            else echo '<div class="jumbotron"><h2 class="cover-heading text-muted">Veuillez choisir une table depuis le menu déroulant.</h2></div>';
        ?>
        <?php
        include_once 'includes/header.php';
        $conn->query('USE Eleves;')  or die($conn->error);
        //print_r($tables[$_POST['bdd']]);
        if(isset($_GET['bdd'])) {
            $sql = 'SELECT * FROM '.$_GET['bdd'].';';
            //echo $sql;
            $result = $conn-> query($sql) or die($conn->error);

        ?>


        <form action="includes/process.php?bdd=<?php echo $_GET['bdd'];?>" method="POST">
            <table class="table table-hover">
                <thead>
                <tr>
                    <?php foreach ($tables[$_GET['bdd']] as $attribut){echo '<th>'.$attribut.'</th>';};?>
                    <th>Editer ou supprimer la ligne</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()):?>
                    <tr>
                        <?php
                        if($_GET['bdd']=='Repartition') $valeur = $row['ID'];
                        else $valeur = $row[$tables[$_GET["bdd"]][0]];
                        if(isset($_GET['edit']) && $_GET['edit']==$valeur){
                            foreach ($tables[$_GET['bdd']] as $attribut){echo '<th id="edit"><input id="msg" type="text" class="form-control" name="'.$attribut."-".$_GET['bdd'].'" placeholder="'.$row[$attribut].'"></input>';}
                            unset($_GET['edit']);
                            echo '<th><button class="btn btn-primary" name="confirmer" value="'.$valeur.'">Confirmer</button></th>';
                        } else {
                            foreach ($tables[$_GET['bdd']] as $attribut){echo '<th>'.$row[$attribut].'</th>';}

                            echo '<th>
                                    <button class="btn btn-info" name="edit" value="'.$valeur.'">Edit</button>
                                    <button class="btn btn-danger" name="delete" value="'.$valeur.'">Delete</button>
                                  </th>';
                        };?>
                    </tr>
                <?php endwhile; ?>
                <tr id ='add'>
                    <?php foreach ($tables[$_GET['bdd']] as $attribut){echo '<th id="add"><input id="msg" type="text" class="form-control" name="'.$attribut.'"></th>';};?>
                    <th>
                        <button class="btn btn-warning" name="add" value="<?php echo $_GET['bdd'];?>">Add</button>
                    </th>
                </tr>
                </tbody>
            </table>
        </form>

    </div><!--/row-->

    <hr>
    <?php } $conn->close(); ?>
    <footer>
        <p>&copy; 2019 Projet Base de données, Anas et Maxime.</p>
    </footer>

</div><!--/.container-->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="includes/jquery-1.12.4.min.js" ></script>
<script>window.jQuery || document.write('<script src="includes/jquery.min.js"><\/script>')</script>
<script src="includes/bootstrap.min.js"></script>
<script src="includes/offcanvas.js"></script>
</body>
</html>
