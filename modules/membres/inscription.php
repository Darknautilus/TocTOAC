<?php

$error = array();
$values = array("prenom"=>"","nom"=>"","email"=>"");

if(isset($_POST["filled"]) && $_POST["filled"] == "true") {
	
	$bdd = new BDD();
	
	if(isset($_POST["prenom"]) && !empty($_POST["prenom"]))
		$values["prenom"] = $_POST["prenom"];
	else
		$error[] = "Vous devez donner un prénom";

	if(isset($_POST["nom"]) && !empty($_POST["nom"]))
		$values["nom"] = $_POST["nom"];
	else
		$error[] = "Vous devez donner un nom";

	if(isset($_POST["email"]) && !empty($_POST["email"]))
		$values["email"] = $_POST["email"];
	else
		$error[] = "Vous devez donner une adresse e-mail";

	if(isset($_POST["motDePasse"]) && !empty($_POST["motDePasse"])) {
		if(!isset($_POST["verifMotDePasse"]) || $_POST["motDePasse"] != $_POST["verifMotDePasse"])
			$error[] = "Les mots de passe doivent être identiques";
	}
	else {
		$error[] = "Vous devez définir un mot de passe";
	}

	if(empty($error)) {
		//Insertion du nouveau membre dans la table membre
		$result = $bdd->insert("Members", array("membmail" => $_POST["email"], "membfirstname" => $_POST["prenom"], "memblastname" => $_POST["nom"], "membpasswd" => $_POST["motDePasse"]));
		if(!$result)
			$error[] = "Erreur insertion : ".$bdd->getLastError();
	}
	
	$bdd->close();
}

echo $twig->render("membres_inscription.html", array("errors" => $error, "values" => $values));