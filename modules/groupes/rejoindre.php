<?php

$error = array();

if (isLogged())
{
	$idgroup = $_GET['idGroupe'];
	$idmemb = $GLOBALS["membinfos"]['membid'];
	
	$bdd = new BDD();
	
	//Le groupe existe-t-il?
	$idIdGroupValid = $bdd->select("SELECT grpid FROM Groups WHERE grpid = ".$idgroup.";");
		
	
	//Si le groupe est trouvé dans la base, on continue
	if($idIdGroupValid)
	{
		
		//On récupère les infos concernant le groupe pour éventuellement les afficher
		$groupe = $bdd->select("SELECT g.grpid, g.grpname, g.visibility, g.description
				FROM Groups g
				WHERE g.grpid = $idgroup");
		
		//On vérifie que l'utilisateur ne soit pas déjà dans ce groupe
		$test = $bdd->select("SELECT o.grp, o.member, o.grnt
				FROM Own o
				WHERE o.grp = $idgroup
				AND o.member = $idmemb");
				
		//Si c'est le cas, on ajoute une erreur dans le tableau
		if($test)
		{
			$error[] = "Impossible de rejoindre : Vous appartenez déjà ce groupe" ;
		}
		//Sinon, on appelle la page html
		else
		{
			//On insère l'utilisateur dans la table own
			$result = $bdd->insert("Own", array("grp" => $idgroup, "member" => $idmemb, "grnt" => 1));
			// on met à jour la liste des groupes du membre
			majGrpMb();
		}
	}
	//Si le groupe n'est pas trouvé
	else 
	{
		$error[] = "Ce groupe n'existe plus..." ;
	}
	
	$bdd->close();
}
//Si l'utilisateur n'est pas connecté
else
{
	$error[] = "Vous devez être connecté pour pouvoir rejoindre un groupe";
}
var_dump($error);
echo $twig->render("index_show.html", array("errors" => $error));



