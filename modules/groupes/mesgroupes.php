<?php

$bdd = new BDD();

if(isLogged()) {
	$member = loggedMember();
	
	$groupes = $bdd->select("select g.grpid, g.grpname, g.description, g.visibility from Groups as g, Members as m, Own as o
			where o.grp = g.grpid
			and o.member = ".$member["id"]."
			and m.membid = ".$member["id"].";");
	if(!$groupes)
		$groupes = array();
	
	$bdd->close();
	echo $twig->render("groupes_mesgroupes.html", array("groupes" => $groupes));
}
else {
  $bdd->close();
	header("Location:".queries("", "", array()));
}
