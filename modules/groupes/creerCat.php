<?php
$error = array();

if(isLogged() && isset($_GET["id"]) && isset($_GET["nom"])) {
	
	
	$nom = $_GET["nom"];
	$values = array("Libelle"=>"", "nom" => $nom, "grpid"=>$_GET["id"], "grpname"=>$_GET["nom"]);
	
	
	
	if(isset($_POST["filled"]) && $_POST["filled"] == "true") {
	
		$bdd = new BDD();
	
		if(isset($_POST["libelle"]) && !empty($_POST["libelle"]))
			$values["libelle"] = $_POST["libelle"];
		else
			$error[] = "Vous devez donner un nom à votre catégorie...";
	
		if(empty($error)) {
			//Insertion de la nouvelle catégorie dans la table Categories
			$result = $bdd->insert("Categories", array("catlabel" => $_POST["libelle"], "grp" => $_GET["id"] ));
			if(!$result)
				$error[] = "Erreur insertion : ".$bdd->getLastError();
		}
 		//header("Location:index.php?module=groupes&action=afficher_groupes");
		$bdd->close();
	}
	
	
	echo $twig->render("groupe_categorie.html", array("errors" => $error, "values" => $values));
}
else
{
	echo $twig->render("index_show.html", array());
}

