<?php

function majGrpMb() {
	$bdd = new BDD();
	$buffer = $bdd->select("select o.grp from Own o
									where o.member = ".$_SESSION["membid"].";");
	$grpMb = array();
	if($buffer) {
		foreach($buffer as $line) {
			$grpMb[] = $line["grp"];
		}
	}
	$_SESSION["grpMb"] = $grpMb;
	$bdd->close();
}

function majGrpMbPlus() {
	$bdd = new BDD();
	$buffer = $bdd->select("select o.grp from Own o
									where o.member = ".$_SESSION["membid"]." AND
									o.grnt = 2");
	$grpMbPlus = array();
	if($buffer) {
		foreach($buffer as $line) {
			$grpMbPlus[] = $line["grp"];
		}
	}
	$_SESSION["grpMbPlus"] = $grpMbPlus;
	$bdd->close();
}