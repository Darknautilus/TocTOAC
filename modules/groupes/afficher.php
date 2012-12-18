<?php

$id= $_GET['idGroupe'];

$bdd=new BDD();

// Informations groupe
$groupe = $bdd->select("Select * from groups
						where grpId = $id ;");	

// on récupère tous les membres appartenants a un groupe
$mem = $bdd->select("SELECT m.membId, m.membFirstName, m.membLastName
						FROM members AS m, groups AS g, own AS o
						WHERE g.grpId =$id
						AND o.grp = g.grpId
						AND o.member = m.membId");

if( !$mem )
{
	$error = $bdd->getLastError();
}

// Nombre de membres
$nbMemb = count($mem);

// On récupère tous les events liés au groupe
$event = $bdd->select("Select e.eventId, e.eventName, e.date, e.time,  m.membFirstName, m.membLastName
						From Events as e, members as m
						Where grp= $id
						And e.creator = m.membId;");

echo $twig->render("groupes_afficher.html", array("groupe" => $groupe,  "membres" => $mem, "nbMembres" => $nbMemb  , "events" => $event));