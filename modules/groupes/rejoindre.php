<?php

$errors = array();

if (isLogged() && !isMb($_GET["idGroupe"]))
{
	$idgroup = $_GET['idGroupe'];
	$membre = loggedMember();
	
	$bdd = new BDD();
	
	//Si le groupe est trouvé dans la base, on continue
	if($bdd->exists("Groups", "grpid", $idgroup))
	{
			//On insère l'utilisateur dans la table own
			$result = $bdd->insert("Own", array("grp" => $idgroup, "member" => $membre["id"], "grnt" => 1));
			if(!$result)
			  $errors[] = "Erreur insertion lien membre-groupe";
			// on met à jour la liste des groupes du membre
			majGrpMb();
	}
	//Si le groupe n'est pas trouvé
	else 
	{
		$errors[] = "Groupe introuvable" ;
	}
	
	$bdd->close();
	if(empty($errors))
	  header("Location:".queries("groupes", "details", array("idGroupe" => $idgroup)));
	else
	  echo $twig->render("global_error.html", array("errors" => $errors, "titre" => "Rejoindre un groupe"));
}
//Si l'utilisateur n'est pas connecté ou est déjà membre du groupe
else
{
	header("Location:".queries("","",array()));
}



