<?php
    $tables = array("Eleve" => array("ElevID","Nom","Age","Ville"),"Activites" => array ("ActID","Lieu","Bus","Theme","jour"),"Classes"=>array("ClasID","Enseignant"),"Repartition" => array("ElevID","ClasID","ActID"));

    $servername = "localhost";
	$username = "root";
	$password = "";
	// Create connection
	$conn = new mysqli($servername, $username, $password);
	// Check connection

	if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
	}

