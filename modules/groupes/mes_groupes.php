<?php

$bdd = new BDD();

$groupes = $bdd -> select("Select grpId, grpName, membId From groups");

/*$groupes = $bdd -> select("Select g.grpId, g.grpName, m.membId From groups as g, members as m, own as o
						   Where o.grp = g.grpId
						   And o.member = $_SESSION['memId']
						   And m.memId = $_SESSION['memId']"); */

echo $twig->render("mes_groupes.html", array("listGrps" => $groupes));