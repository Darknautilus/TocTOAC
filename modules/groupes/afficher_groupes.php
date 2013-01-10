<?php

$bdd = new BDD();

$error = "";

$groupes = $bdd->select("select grpid, grpname, nbmemb from Groups;");
if(!$groupes)
	$error .= $bdd->getLastError();

/*$groupes = $bdd -> select("Select g.grpId, g.grpName, m.membId From groups as g, members as m, own as o
						   Where o.grp = g.grpId
						   And o.member = $_SESSION['memId']
						   And m.memId = $_SESSION['memId']"); */

// Nombre de membres
//$nbMembres = $bdd -> select(""); , "nbMembres" =>

$nbGroupes = count($groupes);

$bdd->close();


echo $twig->render("groupes_afficher_groupes.html", array("listGrps" => $groupes, "nbG" => $nbGroupes));