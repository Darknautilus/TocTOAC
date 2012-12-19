<?php

$error = array();
$values = array("grpName"=>"","description"=>"");

if( isset($_POST["filled"]) && $_POST["filled"] == "true") {
	$bdd=new BDD();
	
	if(isset($_POST["nomGroupe"]) && !empty($_POST["nomGroupe"]))
		$values["grpName"] = $_POST["nomGroupe"];
	else
		$error[] = "Vous devez entrer un nom de groupe";
	
	if(isset($_POST["description"]) && !empty($_POST["description"]))
		$values["description"] = $_POST["description"];
	else
		$error[] = "Vous devez entrer une description";
		
	if(empty($error)) {
		$result = $bdd->insert("groups", array( "grpName" => $_POST['nomGroupe'], "visibility" => 1, "description" => $_POST['description']));
		if(!$result)
			$error[] = "Erreur création de groupe : ".$bdd->getLastError();
	}
	
	$bdd->close();
	
}

echo $twig->render("groupe_creer.html", array("errors" => $error, "values" => $values));