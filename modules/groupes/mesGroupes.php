<?php

$bdd = new BDD();

$groupes = $bdd -> select("Select g.grpId, g.grpName, m.membId From groups as g, members as m 
						   Where ");

echo $twig->render("groupes_afficher.html", array());
