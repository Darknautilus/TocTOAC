<?php

$bdd = new BDD();

$error = "";

if(isLogged()) {
	$memberId = $GLOBALS["membinfos"]["membid"];
	
	$groupes = $bdd->select("select grpid, grpname, nbmemb from Groups as g, Members as m, Own as o
			where o.grp = g.grpId
			and o.member = $memberId
			and m.membid = $memberId;");
	if(!$groupes)
		$error .= $bdd->getLastError();
	
	/* logged = true si loggé */
	
	
	$nbGroupes = count($groupes);
	
	$bdd->close();
	
	
	echo $twig->render("groupes_mesgroupes.html", array("listGrps" => $groupes, "nbG" => $nbGroupes));
}
else {
	echo $twig->render("index_show.html", array());
}
