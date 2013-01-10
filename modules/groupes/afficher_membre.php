<?php

$id= $_GET['idGroupe'];

$bdd=new BDD();

// Informations groupe
$groupe = $bdd->select("Select grpid, grpname, visibility, description from Groups
		where grpid = $id ;");

// on récupère tous les membres appartenants a un groupe
$mem = $bdd->select("SELECT m.membid, m.membfirstname, m.memblastname
		FROM Members AS m, Groups AS g, Own AS o
		WHERE g.grpid =$id
		AND o.grp = g.grpid
		AND o.member = m.membid");

if( !$mem )
{
	$error = $bdd->getLastError();
}

// Nombre de membres
$nbMemb = count($mem);


echo $twig->render("groupes_afficher_membre.html", array("groupe" => $groupe,  "membres" => $mem, "nbMembres" => $nbMemb));