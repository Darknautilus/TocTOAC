<?php

$bdd = new BDD();

$error = "";

$memberId = 1;/*$_SESSION['membid'];*/

$groupes = $bdd->select("select grpid, grpname, nbmemb from Groups as g, Members as m, Own as o
						 where o.grp = g.grpId
						 and o.member = $memberId
						 and m.membid = $memberId;");
if(!$groupes)
	$error .= $bdd->getLastError();

/* logged = true si loggé */


$nbGroupes = count($groupes);

$bdd->close();


echo $twig->render("groupes_afficher_groupes.html", array("listGrps" => $groupes, "nbG" => $nbGroupes));