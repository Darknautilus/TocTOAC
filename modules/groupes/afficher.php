<?php

$id= $_GET('idGroupe');

$bdd=new BDD();

$mem = $bdd->select("Select m.membId, m.membName from members as m, group as g, own as o Where g.gprId = $id and o.grp = g.gprId And o.member = m.membId;");

if( !$gp )
{
	$error = $bdd->getLastError();
}


echo $twig->render("groupes_afficher.html", array("membres" => $mem ));