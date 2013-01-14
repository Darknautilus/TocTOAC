<?php

$error = array();

$idgroup = $_GET['idGroupe'];

$idmemb = $_SESSION['member'];

$bdd = new BDD();

//On récupère les infos concernant le groupe pour éventuellement les afficher
$groupe = $bdd->select("SELECT g.grpid, g.grpname, g.visibility, g.description 
							FROM Groups AS g
							WHERE g.grpid = $idgroup");

//On vérifie que l'utilisateur ne soit pas déja dans ce groupe
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
	//On insère l'utilisateur dans la table own
	$test2 = $bdd->insert(Own, array("grp" => $idgroup, "member" => $idmemb, "grnt" => 1));
}


echo $twig->render("groupes_rejoindre.html", array("groupe" => $groupe, "errors" => $errors));
