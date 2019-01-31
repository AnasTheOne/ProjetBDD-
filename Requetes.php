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
                <li class="active"><a href="Requetes.php">Requêtes</a></li>

                <li class="nav-item dropdown">
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

        <div class="jumbotron"><h2 class="cover-heading text-muted">Interrogation de la base</h2></div>

        <?php
        include_once 'includes/header.php';
        $requetes = array(  'SELECT Nom,Ville FROM Eleve ORDER BY Ville;',
            'SELECT DISTINCT el.Nom as Nom ,cl.Enseignant as Enseignant FROM Repartition as rep INNER JOIN Eleve as el ON el.ElevID=rep.ElevID INNER JOIN Classes as cl ON cl.ClasID=rep.ClasID;',
            'SELECT act.Bus Bus, COUNT(*) as \'Eleves Par Bus\' FROM Repartition rep INNER JOIN Activites act ON rep.ActID=act.ActID INNER JOIN Eleve el ON rep.ElevID=el.ElevID GROUP BY act.Bus;',
            'SELECT DISTINCT Enseignant, ActID as Activite FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID WHERE cl.ClasID=(SELECT reps.ClasID FROM Repartition reps WHERE reps.ActID = rep.ActID GROUP BY reps.ClasID ORDER BY Count(*) DESC LIMIT 1);',
            'SELECT el.Nom FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID INNER JOIN Eleve el ON el.ElevID=rep.ElevID GROUP BY rep.ElevID HAVING COUNT(*)>1;',
            'SELECT el.Nom FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID INNER JOIN Eleve el ON el.ElevID=rep.ElevID GROUP BY rep.ElevID HAVING COUNT(*)=(SELECT COUNT(DISTINCT jour) as nombre FROM Activites);',
            'SELECT DISTINCT el.Nom FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID INNER JOIN Eleve el ON el.ElevID=rep.ElevID WHERE el.Ville=act.Lieu;',
            'SELECT T1.activite AS Activite1,T2.activite AS Activite2 FROM (SELECT act.ActID as activite, COUNT(*) as nombre FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID GROUP BY act.ActID) T1 JOIN (SELECT act.ActID as activite, COUNT(*) as nombre FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID GROUP BY act.ActID) T2 WHERE T1.nombre=T2.nombre AND T1.activite!=T2.activite;',
            'SELECT act.ActID as Activite FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID GROUP BY act.ActID ORDER BY COUNT(*) DESC;',
            'SELECT * FROM (SELECT cl.Enseignant as Nom, COUNT(*) AS num FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID GROUP BY cl.ClasID) maTable WHERE maTable.num > (SELECT COUNT(cl.ClasID) FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID)/(SELECT COUNT(ClasID) FROM Classes);',
            'SELECT ActID, SUM(el.Age)/COUNT(el.Age) as "Moyenne d\'age" FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID INNER JOIN Eleve el ON el.ElevID=rep.ElevID GROUP BY ActID;',
            'SELECT ActID , min(el.Age) as "Minimum d\'age" , max(el.Age) as "Maximum d\'age" FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID INNER JOIN Eleve el ON el.ElevID=rep.ElevID GROUP BY ActID;',
            'SELECT rep.ActID as Activite,(SELECT els.Ville FROM Repartition reps INNER JOIN Classes cls ON cls.ClasID=reps.ClasID INNER JOIN Eleve els ON els.ElevID=reps.ElevID WHERE reps.ActID = Activite GROUP BY els.Ville ORDER BY COUNT(*) DESC LIMIT 1) as Ville FROM Repartition rep INNER JOIN Classes cl ON cl.ClasID=rep.ClasID INNER JOIN Eleve el ON el.ElevID=rep.ElevID GROUP BY rep.ActID;',
            'SELECT cl.Enseignant As Prof , el.Nom as Nom FROM Classes cl INNER JOIN Repartition rep ON rep.ClasID=cl.ClasID INNER JOIN Eleve el ON el.ElevID=rep.ElevID ;',
            'SELECT el.Nom,rep.ActID as Activite,act.jour as Jour FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID INNER JOIN Eleve el ON el.ElevID=rep.ElevID WHERE el.Ville=act.Lieu;',
            'SELECT DISTINCT jour as Jour, Bus FROM Activites;',
            'SELECT DISTINCT el.Nom FROM Repartition rep INNER JOIN Activites act ON act.ActID=rep.ActID INNER JOIN Eleve el ON el.ElevID=rep.ElevID WHERE el.Ville!=act.Lieu;',
            'SELECT Count(Distinct Bus) as "Nombre de bus necessaires" FROM Activites;',
            'SELECT SUM(Age)/Count(Age) "Age moyen des eleves" FROM Eleve;');
        $texte = array( '1. La liste de tous les élèves triés en fonction de leur domicile.',
            '2. La liste des élèves par enseignant.',
            '3. Donner le nombre d’élèves par bus en fonction du jour.',
            '4. On décide de faire surveiller les activités par l’enseignant dont les élèves sont les plus nombreux dans l’activité. Donner pour chaque activité le ou les enseignants qui pourraient être proposés pour l’encadrer, en accord avec le principe énoncé.',
            '5. Donner les élèves qui ont plusieurs activité par jour.',
            '6. Donner les élèves qui ont une activité tous les jours proposés.',
            '7. Donner les élèves qui ont une activité dans leur ville de résidence.',
            '8. Donner les activités qui ont les mêmes nombres d’élèves.',
            '9. Donner la liste des activités triées par nombre décroissant d’élèves.',
            '10. Lister le nom des enseignants qui ont plus d’élèves que la moyenne par classe.',
            '11. Donner la moyenne d’âge par activité.',
            '12. Donner par activité l’amplitude d’âge entre le plus jeune et le plus âgé des élèves.',
            '13. Donner pour chaque activité la(les) ville(s) où habite de la majorité des élèves.',
            '14. Pour chaque enseignant, donner la liste de ses élèves.',
            '15. On considère qu’un élève qui fait une activité dans sa ville ne prend pas le bus. Donner la liste des élèves qui ne prendrons pas le bus avec leur activité et le jour.',
            '16. Donner les bus utilisés chaque jour.',
            '17. Donner les élèves qui n\'ont pas une activité dans leur ville de résidence.',
            '18. Donner le nombre de bus nécessaires.',
            '19. Donner l\'age moyen des élèves.');

        $conn->query('USE Eleves;')  or die($conn->error);

        for ($i = 1; $i <= count($requetes);$i++){
            echo '<h2><u>Requête n°'.$i.':</u></h2>';
            echo '<p><h4>'.$texte[$i-1].'</h4></p>';
            echo '<a class="btn btn-info" href="Requetes.php?request='.$i.'#tableau">Executer cette requête.</a>';
        };
        if(isset($_GET['request'])){
            $result = $conn->query($requetes[$_GET['request']-1]) or die(print_r($conn->error_list));

            ?>


            <form action="" method="GET">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <?php
                        $row = $result->fetch_assoc();
                        foreach (array_keys($row) as $attribut){echo '<th id="tableau">'.$attribut.'</th>';} ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    echo '<tr>';
                    foreach (array_keys($row) as $attribut){echo '<th>'.$row[$attribut].'</th>';}
                    echo '</tr>';
                    while ($row = $result->fetch_assoc()):
                        echo '<tr>';
                        foreach (array_keys($row) as $attribut){echo '<th>'.$row[$attribut].'</th>';}
                        echo '</tr>';
                    endwhile; ?>

                    </tbody>
                </table>
            </form>

        <?php  }$conn->close(); ?>
    </div><!--/row-->

    <hr>
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
