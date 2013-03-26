<?php

function majGlobals() {
  if(isset($_COOKIE["logged"])) {
    $GLOBALS["logged"] = $_COOKIE["logged"];
    $GLOBALS["membinfos"] = getCookie("membinfos");
  }
  else if(isset($_SESSION["logged"])) {
    $GLOBALS["logged"] = $_SESSION["logged"];
    $GLOBALS["membinfos"] = $_SESSION["membinfos"];
  }
  else {
    $GLOBALS["logged"] = false;
  }

  if($GLOBALS["logged"]) {
    if(!isset($_SESSION["grpMb"])) {
      majGrpMb();
      majGrpMbPlus();
    }
  $GLOBALS["grpMb"] = $_SESSION["grpMb"];
  $GLOBALS["grpMbPlus"] = $_SESSION["grpMbPlus"];
  }
}
majGlobals();

function creerCookie($name, $value) {
  if(is_array($value)) {
    $cookie = "";
    foreach ($value as $key => $field) {
      $cookie .= $key."=".$field.";;";
    }
  }
  else {
    $cookie = $value;
  }
  setcookie($name, urlencode($cookie), time()+3600*24*365);
}

function getCookie($name) {
  if(!isset($_COOKIE[$name])) {
    return false;
  }
  else if(!strpos(urldecode($_COOKIE[$name]), ";;")) {
    return urldecode($_COOKIE[$name]);
  }
  else {
    $values = array();
    $couples = explode(";;", urldecode($_COOKIE[$name]));
    foreach($couples as $couple) {
      $list = explode("=",$couple);
      if(!empty($list[0]))
        $values[$list[0]] = $list[1];
    }
    return $values;
  }  
}

function majGrpMb() {
	$bdd = new BDD();
	$buffer = $bdd->select("select o.grp from Own o
									where o.member = ".$GLOBALS["membinfos"]["membid"].";");
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
									where o.member = ".$GLOBALS["membinfos"]["membid"]." AND
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

function envoyerMail($adresseDest,$objet,$message)
{
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  $headers .= 'From: Admin TocTOAC <moi@mail.fr>' . "\r\n";

  mail($adresseDest, "[TocTOAC]".$objet, $message, $headers);
}

