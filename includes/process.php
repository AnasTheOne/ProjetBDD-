

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="icon.ico">

    <title>Projet BDD</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="stylesheet.css" rel="stylesheet">
    <link href="cover.css" rel="stylesheet">
    <link href="offcanvas.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<script src="jquery-3.3.1.slim.min.js" ></script>
<script src="popper.min.js" ></script>
<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand" >Projet BDD</div>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../index.php">Home</a></li>
                <li><a href="../remplir.php">Remplir</a></li>
                <li><a href="../Requetes.php">Requêtes</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="../display.php" id="navbardrop" data-toggle="dropdown">
                        Tables
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-center">
                        <a class="btn btn-dark text-center" href="../display.php?bdd=Activites">Activites</a>
                        <div class="dropdown-divider"></div>
                        <a class="btn btn-dark text-center" href="../display.php?bdd=Classes">Classes</a>
                        <div class="dropdown-divider"></div>
                        <a class="btn btn-dark text-center" href="../display.php?bdd=Eleve">Eleve</a>
                        <div class="dropdown-divider"></div>
                        <a class="btn btn-dark text-center" href="../display.php?bdd=Repartition">Repartition</a>
                    </div>
                </li>
            </ul>
        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar -->

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">


            <?php

            include_once 'header.php';
            if(isset($_POST['add'])){

                if($_POST['add'] == 'Repartition'){
                    $i = 0; $sqlsecond = 'SELECT COUNT(*) as number FROM Repartition WHERE ';
                    foreach ($tables[$_POST['add']] as $attribut){
                        $i++;
                        if($i != count($tables[$_POST['add']])) {
                            $sqlsecond = $sqlsecond . $attribut . '='. "'$_POST[$attribut]' AND ";
                        } else {
                            $sqlsecond = $sqlsecond . $attribut . '='. "'$_POST[$attribut]'; ";
                        }
                    }

                    $conn->query('USE Eleves;')  or die($conn->error);
                    echo $sqlsecond.'<br>';
                    $result = $conn->query($sqlsecond) or die(print_r($conn->error_list));
                    if($result->fetch_assoc()['number']==0){
                        $sql = "INSERT INTO ".$_POST['add'] ." (";
                        $i = 0;
                        for(; $i < count($tables[$_POST['add']])-1; $i++ ){
                            $sql =  $sql . $tables[$_POST['add']][$i]. ",";
                        }
                        $sql =  $sql . $tables[$_POST['add']][$i]. ")";
                        $sqlprime = " values(";
                        $i = 0;
                        foreach ($tables[$_POST['add']] as $attribut){
                            $i++;
                            if($i != count($tables[$_POST['add']])) {
                                $sqlprime = $sqlprime . "'$_POST[$attribut]',";
                            } else {
                                $sqlprime =  $sqlprime."'" .$_POST[$attribut]. "');";
                            }
                        }
                        echo $sqm.$sqlprime.'<br>';
                        $conn->query($sql.$sqlprime) or die(print_r($conn->error_list));

                    }
                } else {
                    $conn->query('USE Eleves;')  or die($conn->error);
                    $sql = "INSERT INTO ".$_POST['add'] ." (";
                    $i = 0;
                    for(; $i < count($tables[$_POST['add']])-1; $i++ ){
                        $sql =  $sql . $tables[$_POST['add']][$i]. ",";
                    }
                    $sql =  $sql . $tables[$_POST['add']][$i]. ")";
                    $sqlprime = " values(";
                    $i = 0;
                    foreach ($tables[$_POST['add']] as $attribut){
                        $i++;
                        if($i != count($tables[$_POST['add']])) {
                            $sqlprime = $sqlprime . "'$_POST[$attribut]',";
                        } else {
                            $sqlprime =  $sqlprime."'" .$_POST[$attribut]. "');";
                        }
                    }
                    $conn->query($sql.$sqlprime) or die(print_r($conn->error_list));
                }
                $location = 'Location: ../display.php?bdd='.$_POST['add'].'#add';
                header($location);

            }

            if(isset($_POST['delete'])){

                $conn->query('USE Eleves;')  or die($conn->error);
                if($_GET['bdd']=='Repartition') $sql = 'DELETE FROM '. $_GET['bdd'].' WHERE ID='.$_POST['delete'].";";
                else $sql = 'DELETE FROM '. $_GET['bdd'].' WHERE '.$tables[$_GET['bdd']][0]."=".$_POST['delete'].";";
                echo $sql.'<br>';
                $conn->query($sql.$sqlprime) or die(print_r($conn->error_list));
                $location = 'Location: ../display.php?bdd='.$_GET['bdd'];
                header($location);
            }

            if(isset($_POST['confirmer'])){

                $conn->query('USE Eleves;')  or die($conn->error);
                $sql = "UPDATE ".$_GET['bdd'] ." SET ";
                $i = 0;
                for(; $i < count($tables[$_GET['bdd']])-1; $i++ ){
                    $att = $tables[$_GET['bdd']][$i].'-'.$_GET['bdd'];
                    $sql =  $sql . $tables[$_GET['bdd']][$i]." = " ."'".$_POST[$att]."'". ",";
                }
                $att = $tables[$_GET['bdd']][$i].'-'.$_GET['bdd'];
                $sql =  $sql . $tables[$_GET['bdd']][$i]." = " ."'".$_POST[$att]."'". "";
                if($_GET['bdd']=='Repartition') $sql =  $sql . " WHERE ID = ". $_POST['confirmer']. ";" ;
                else $sql =  $sql . " WHERE ".$tables[$_GET['bdd']][0]." = ". $_POST['confirmer']. ";" ;
                echo $sql.'<br>';
                $conn->query($sql.$sqlprime) or die(print_r($conn->error_list));
                $location = 'Location: ../display.php?bdd='.$_GET['bdd'];
                header($location);

            }

            if(isset($_POST['edit'])){

                //$conn->query($sql.$sqlprime) or die(print_r($conn->error_list));
                $location = 'Location: ../display.php?bdd='.$_GET['bdd'].'&edit='.$_POST['edit'].'#edit';
                header($location);
            } ?>


    </div><!--/row-->

    <hr>
    <?php $conn->close(); ?>
    <footer>
        <p>&copy; 2019 Projet Base de données, Anas et Maxime.</p>
    </footer>

</div><!--/.container-->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="jquery-1.12.4.min.js" ></script>
<script>window.jQuery || document.write('<script src="jquery.min.js"><\/script>')</script>
<script src="bootstrap.min.js"></script>
<script src="offcanvas.js"></script>
</body>
</html>
