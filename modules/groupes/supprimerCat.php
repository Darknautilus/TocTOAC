<?php
$error = array();
$bdd = new BDD();
$categories = null;
$grp=$_GET["idGroupe"];

if(isLogged() && isset($_GET["idGroupe"])) {
	
	//Suppression
	if(isset($_POST["supr"]) && $_POST["supr"] == "true" && !empty($_POST["idcat"])) {		
		if(empty($error)) {

			//Suppression catégorie dans la table Categories
			$result = $bdd->delete("Categories", array( "catid" => $_POST["idcat"]  ));
			if(!$result)
				$error[] = "Erreur insertion : ".$bdd->getLastError();
		}
		//header("Location:index.php?module=groupes&action=afficher_groupes");
		
	}

	//Affichage des catégories
	$categories = $bdd->select("select c.catid, c.catlabel, c.grp from Categories c where c.grp='".$grp."';");
	if(!$categories)
		$errors[] = "Il n'y a rien à afficher";
	
	$bdd->close();
	echo $twig->render("groupes_modifier_supprimerCat.html", array("id" => $grp, "categories" => $categories));
}