<?php

$error = array();

$idgroup = $_GET['idGroupe'];

$idmemb = $_SESSION['member'];

$bdd = new BDD();

//On r�cup�re les infos concernant le groupe pour �ventuellement les afficher
$groupe = $bdd->select("SELECT g.grpid, g.grpname, g.visibility, g.description 
							FROM Groups AS g
							WHERE g.grpid = $idgroup");

//On v�rifie que l'utilisateur ne soit pas d�ja dans ce groupe
$test = $bdd->select("SELECT o.grp, o.member, o.grnt 
						FROM Own AS o
						WHERE o.grp = $idgroup
						AND o.member = $idmemb");

if($test)
{
	$error[] = "Vous appartenez deja a ce groupe" ;
}
else 
{
	//On ins�re l'utilisateur dans la table own
	$test2 = $bdd->insert(Own, array("grp" => $idgroup, "member" => $idmemb, "grnt" => 1));
}


echo $twig->render("groupes_rejoindre.html", array("groupe" => $groupe, "errors" => $errors));
