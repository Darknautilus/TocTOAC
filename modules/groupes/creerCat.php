<?php

$bdd = new BDD();
$error = array();
$values = array();

if(isLogged() && isset($_GET["grpid"]) && $bdd->exists("Groups", "grpid", $_GET["grpid"]) && isMbPlus($_GET["grpid"])) {
	$grpid = $_GET["grpid"];
	$groupe = $bdd->select("select grpid, grpname from Groups where grpid = ".$grpid.";");
	$groupe = $groupe[0];
  
	if(isset($_POST["filled"]) && $_POST["filled"] == "true") {
	
		if(isset($_POST["libelle"]) && !empty($_POST["libelle"]))
			$values["libelle"] = $_POST["libelle"];
		else
			$error[] = "Vous devez donner un nom à votre catégorie...";
	
		if(empty($error)) {
			//Insertion de la nouvelle catégorie dans la table Categories
			$result = $bdd->insert("Categories", array("catlabel" => $_POST["libelle"], "grp" => $grpid ));
			if(!$result)
				$error[] = "Erreur insertion : ".$bdd->getLastError();
		}
		$bdd->close();
	}
	
	echo $twig->render("groupe_categorie.html", array("errors" => $error, "values" => $values, "groupe" => $groupe));
}
else
{
	$bdd->close();
	header("Location:".queries("","",array()));
}

