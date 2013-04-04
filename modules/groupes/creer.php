<?php
$idmemb = $GLOBALS["membinfos"]['membid'];
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
	  $idGroupe = null;
		$result = $bdd->insert("Groups", array( "grpName" => $_POST['nomGroupe'], "visibility" => 1, "description" => $_POST['description'], "nbmemb" => 0, "nbcat" => 0));
		if(!$result)
			$error[] = "Erreur création de groupe : ".$bdd->getLastError();
		else
		  $idGroupe = $result;
		
		$result = $bdd->insert("Own", array("grp" => $result , "member" =>  $idmemb, "grnt" =>  "2"));
		if(!$result)
			$error[] = "Erreur création de groupe : ".$bdd->getLastError();
		
		// Si aucune erreur, on met à jour les groupes du membre
		if(empty($error)) {
			majGrpMb();
			majGrpMbPlus();
		}
		
		header("Location:".queries("groupes","details",array("idGroupe" => $idGroupe)));
	}
	
	$bdd->close();
	
}

echo $twig->render("groupe_creer.html", array("errors" => $error, "values" => $values));