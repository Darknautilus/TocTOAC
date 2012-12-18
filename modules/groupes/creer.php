<?php
if( isset($_POST['nomGroupe']) ){
	$bdd=new BDD();

	$bdd->insert("groups", array( "grpName" => $_POST['nomGroupe'], "visibility" => 1, "description" => $_POST['description']));
	
	header("Location: index.php?module=groupes&action=mes_groupes");
	exit;
}

echo $twig->render("groupe_creer.html", array());