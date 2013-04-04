<?php

$bdd = new BDD();
$error = array();
$successes = array();

// Si l'utilisateur est connecté on affiche la page de modification des informations du groupe
if(isLogged() && $bdd->exists("Groups","grpid",$_GET['idGroupe'])) {
	$member = loggedMember();
	
	// Récupération des informations existantes du groupe
	$groupe = $bdd->select("select grpid, grpname, description, nbmemb, nbcat from Groups
			where grpid = ".$_GET['idGroupe'].";");
	$groupe = $groupe[0];
	
	//Récupération des catégories
	$cat = $bdd->select("select catid, catlabel, grp from Categories where grp='".$groupe["grpid"]."';");
	if(!$cat)
	  $cat = array();

	// Enregistrement des modifications
	if( isset($_POST["filled"]) && $_POST["filled"] == "true") {
	
		if(isset($_POST["nomGroupe"]) && !empty($_POST["nomGroupe"]))
		{
			$values["grpName"] = $_POST["nomGroupe"];
			$successes[] = "Nom ok";
		}
		else
			$error[] = "Vous devez entrer un nom de groupe";
	
		if(isset($_POST["description"]) && !empty($_POST["description"]))
		{
			$values["description"] = $_POST["description"];
			$successes[] = "Description ok";
		}
		else
			$error[] = "Vous devez entrer une description";
	
		if(empty($error)) {
			$result = $bdd->update("Groups", array( "grpname" => $_POST['nomGroupe'], "description" => $_POST['description']), array( "grpid" => $groupe["grpid"]));
			if(!$result)
				$error[] = "Erreur de mise à jour du groupe : ".$bdd->getLastError();
		}
	}
	
	$bdd->close();
	echo $twig->render("groupe_modifier.html", array("infosGrp" => $groupe, "categories" => $cat, "errors" => $error, "success" => $successes));
}
else {
  $bdd->close();
	header("Location:".queries("","",array()));
}
