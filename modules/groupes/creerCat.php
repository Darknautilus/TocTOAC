<?php
$error = array();
$nom = $_GET["nom"];
$values = array("Libelle"=>"", "nom" => $nom);



if(isset($_POST["filled"]) && $_POST["filled"] == "true") {

	$bdd = new BDD();

	if(isset($_POST["libelle"]) && !empty($_POST["libelle"]))
		$values["libelle"] = $_POST["libelle"];
	else
		$error[] = "Vous devez donner un nom à votre catégorie...";

	if(empty($error)) {
		//Insertion de la nouvelle catégorie dans la table categorie
		$result = $bdd->insert("categories", array("catLabel" => $_POST["libelle"]));
		if(!$result)
			$error[] = "Erreur insertion : ".$bdd->getLastError();
	}
	
	$bdd->close();
}


echo $twig->render("groupe_categorie.html", array("errors" => $error, "values" => $values));

