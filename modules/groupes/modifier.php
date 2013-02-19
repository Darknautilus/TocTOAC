<?php

$idGroup = $_GET['idGroupe'];

$bdd = new BDD();

$error = array();

// Si l'utilisateur est connecté on affiche la page de modification des informations du groupe
if(isLogged()) {
	$memberId = $GLOBALS["membinfos"]["membid"];
	
	// Récupération des informations existantes du groupe
	$groupe = $bdd->select("select grpid, grpname, description, nbmemb, nbcat from Groups
			where grpid = $idGroup;");
	
	if(!$groupe)
		$error[] = $bdd->getLastError();

	// Enregistrement des modifications
	if( isset($_POST["filled"]) && $_POST["filled"] == "true") {
		
	
		if(isset($_POST["nomGroupe"]) && !empty($_POST["nomGroupe"]))
			$values["grpName"] = $_POST["nomGroupe"];
		else
			$error[] = "Vous devez entrer un nom de groupe";
	
		if(isset($_POST["description"]) && !empty($_POST["description"]))
			$values["description"] = $_POST["description"];
		else
			$error[] = "Vous devez entrer une description";
	
		if(empty($error)) {
			$result = $bdd->update("Groups", array( "grpname" => $_POST['nomGroupe'], "visibility" => 1, "description" => $_POST['description']), array( "grpid" => $idGroup));
			if(!$result)
				$error[] = "Erreur de mise à jour du groupe : ".$bdd->getLastError();
	
			//header("Location:".queries('groupes','details', ???));
			//header("Location:index.php");
		}
	}
	// Suppressions dans la base de données
	if( isset($_POST["supp"]) && $_POST["supp"] == "true") {
		
		if(empty($error)) {
			// Suppression des évènements liés au groupe
			$result = $bdd->delete("Events", array( "grp" => $idGroup));
			if(!$result)
				$error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
			// Suppression des catégories liés au groupe
			$result = $bdd->delete("Categories", array( "grp" => $idGroup));
			if(!$result)
				$error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
			// Suppression de l'appartenance au goupe
			$result = $bdd->delete("Own", array( "grp" => $idGroup));
			if(!$result)
				$error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
			// Suppression du groupe
			$result = $bdd->delete("Groups", array( "grpid" => $idGroup));
			if(!$result)
				$error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();

			header("Location:index.php");
		}
	}
	
	
	$bdd->close();

	echo $twig->render("groupe_modifier.html", array("infosGrp" => $groupe[0]));
}
else {
	echo $twig->render("index_show.html", array());
}
