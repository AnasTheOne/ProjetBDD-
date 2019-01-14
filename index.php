<!--

-->

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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>


<?php include_once 'includes/header.php';?>
<div class="site-wrapper">

    <div class="site-wrapper-inner">

        <div class="cover-container">

            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">Projet BDD</h3>
                    <nav>
                        <ul class="nav masthead-nav">
                            <li class="active"><a href="index.php">Home</a></li>
                            <li><a href="display.php">Tables</a></li>
                            <li><a href="Requetes.php">Requêtes</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="inner cover">
                <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

                <h1 class="cover-heading">Projet base de données.</h1> <br><br>

                    <?php
                    if(isset($_POST['delete'])){
                        $sql = "DROP DATABASE IF EXISTS Eleves;";
                        $conn->query($sql) or die(print_r($conn->error_list));
                        //unset($_POST['delete']);
                        header('index.php');
                    }
                    $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'Eleves'");
                    //echo $result->num_rows;
                    if($result->num_rows == 1 || isset($_POST['creer'])){
                        if(isset($_POST['creer'])) {
                            $multiquery = array('CREATE DATABASE Eleves;',
                                'USE Eleves;',
                                'CREATE TABLE Eleve(ElevID INT PRIMARY KEY NOT NULL, Nom char(30),Age char(30),Ville char(30));',
                                'CREATE TABLE Activites(ActID INT PRIMARY KEY NOT NULL, Lieu char(30),Bus char(30),Theme char(30),jour char(30));',
                                'CREATE TABLE Classes(ClasID INT PRIMARY KEY NOT NULL, Enseignant char(30));',
                                'CREATE TABLE Repartition(ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                                          ElevID INT NOT NULL, 
                                                          ClasID INT NOT NULL,
                                                          ActID INT NOT NULL,
                                                          FOREIGN KEY (ElevID) REFERENCES Eleve (ElevID), 
                                                          FOREIGN KEY (ClasID) REFERENCES Classes (ClasID),
                                                          FOREIGN KEY (ActID) REFERENCES Activites (ActID));');
                            foreach ($multiquery as $sql) {
                                $conn->query($sql) or die(print_r($conn->error_list));
                            }
                        }
                    ?>
                    <p class="lead">Remplissez à partir d'un fichier CSV ou manuellement.</p>
                    <form action="remplir.php" method="post">
                        <p class="lead"><button class="btn btn-info btn-block" name="csv" value="1">Remplir à l'aide d'un csv</button></p>
                    </form>

                    <form action="display.php" method="post">
                    <p class="lead"><button class="btn btn-info btn-block" name="manuel" value="1">Remplir manuellement</button></p>
                    </form>

                    <form action="" method="post">
                        <p class="lead"><button class="btn btn-danger" name="delete" value="<?php echo 'Eleves';?>">Supprimer la base</button></p>
                    </form>

                    <?php } else if ($result->num_rows == 0) {?>
                        <form action="" method="post">
                            <button class="btn btn-lg btn-default" name="creer" value="Eleves">Créer la base Élèves</button>
                        </form>
                    <?php } ?>
            </div>

            <div class="mastfoot">
                <div class="inner">
                    <p>&copy; 2019 Projet Base de données, <a>Anas EL MOUSSAOUI</a> et <a>Maxime LATGE</a>.</p>
                </div>
            </div>

        </div>

    </div>

</div>



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="includes/jquery-1.12.4.min.js" ></script>
<script>window.jQuery || document.write('<script src="includes/jquery.min.js"><\/script>')</script>
<script src="includes/bootstrap.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>