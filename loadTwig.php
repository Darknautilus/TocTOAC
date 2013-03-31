<?php
	include_once(dirname(__FILE__).'/twig/lib/Twig/Autoloader.php');
	
	// Coupe un texte à $longueur caractères, sur les espaces, et ajoute des points de suspension...
	function tronque($chaine, $longueur = 120)
	{

		if (empty ($chaine))
		{
			return "";
		}
		elseif (strlen ($chaine) < $longueur)
		{
			return $chaine;
		}
		elseif (preg_match ("/(.{1,".$longueur."})\s./ms", $chaine, $match))
		{
			return $match [1] . "...";
		}
		else
		{
			return substr ($chaine, 0, $longueur) . "...";
		}
	}

	// Fonctions de construction d'URL
	function root() {
		return "http://".$_SERVER['SERVER_NAME'];
	}
	function templates() {
		return root()."/templates";
	}
	function css() {
		return templates()."/style";
	}
	function images() {
		return templates()."/images";
	}
	function bootstrap() {
	  return templates()."/bootstrap";
	}
	function fontAwesome() {
	  return templates()."/fontAwesome";
	}
	
	function queries($module, $action, $param) {
		$query = root()."/index.php?module=".$module."&action=".$action;
		foreach($param as $key => $value) {
			$query .= "&".$key."=".$value;
		}
		return $query;
	}
	function PROD_MODE() {
		return APP_MODE_PROD;
	}
	function isLogged() {
		return $GLOBALS["logged"];
	}
	function isAdmin() {
		return ($GLOBALS["logged"] && $GLOBALS["membinfos"]["admin"]);
	}
	
	function loggedMember() {
	  return array(
				"id" => $GLOBALS["membinfos"]["membid"],
				"mail" => $GLOBALS["membinfos"]["membmail"],
				"membfirstname" => $GLOBALS["membinfos"]["membfirstname"],
				"memblastname" => $GLOBALS["membinfos"]["memblastname"],
				"membpasswd" => $GLOBALS["membinfos"]["membpasswd"],
				"mesgroupes" => $GLOBALS["grpMb"]
				);
	}
	
	function participate($_event, $_member = null) {
	  $particip = false;
	  if($_member == null) {
	    $member = loggedMember();
	    $member = $member["id"];
	  }
	  else {
	    $member = $_member;
	  }
	  $bdd = new BDD();
	  if($bdd->exists("Members", "membid", $member) && $bdd->exists("Events", "eventid", $_event)) {
	    $part = $bdd->select("select event from Participate where event = ".$_event." and member = ".$member.";");
	    if($part)
	      $particip = true;
	  }
	  $bdd->close();
	  return $particip;
	}
	
	function isCreator($_event, $_member = null) {
	  $creator = false;
	  if($_member == null) {
	    $member = loggedMember();
	    $member = $member["id"];
	  }
	  else {
	    $member = $_member;
	  }
	  $bdd = new BDD();
	  if($bdd->exists("Members", "membid", $member) && $bdd->exists("Events", "eventid", $_event)) {
	    $crea = $bdd->select("select creator from Events where eventid = ".$_event.";");
	    $creator = ($crea[0]["creator"] == $member);
	  }
	  $bdd->close();
	  return $creator;
	}
	
	function isMb($_grpid, $_membid = null) {
		$isMb = false;
		if($_membid == null) {
  		$groupes = $GLOBALS["grpMb"];
  		foreach($groupes as $grpid) {
  			if($grpid == $_grpid)
  				$isMb = true;
  		}
		}
		else {
		  $bdd = new BDD();
		  if($bdd->exists("Members","membid",$_membid)) {
		    $groupes = $bdd->select("select grp from Own where member = ".$_membid.";");
		    if($groupes) {
		      foreach($groupes as $groupe) {
		        if($groupe["grp"] == $_grpid)
		          $isMb = true;
		      }
		    }
		  }
		  $bdd->close();
		}
		return $isMb;
	}
	
	function isMbPlus($_grpid, $_membid = null) {
		$isMbPlus = false;
		if($_membid == null) {
  		$groupes = $GLOBALS["grpMbPlus"];
  		foreach($groupes as $grpid) {
  			if($grpid == $_grpid)
  				$isMbPlus = true;
  		}
		}
		else {
		  $bdd = new BDD();
		  if($bdd->exists("Members","membid",$_membid)) {
		    $groupes = $bdd->select("select o.grp from Own o,Grants g where member = ".$_membid." and g.grantlabel = 'membreplus' and o.grnt = g.grantid;");
		    if($groupes) {
		      foreach($groupes as $groupe) {
		        if($groupe["grp"] == $_grpid)
		          $isMbPlus = true;
		      }
		    }
		  }
		  $bdd->close();
		}
		return $isMbPlus;
	}
	
	function modal( $id, $titre, $question, $rep, $action){
		return "
	<div class=\"modal hide fade\" id=\"$id\">
	    <div class=\"modal-header\">
	    	<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
	    	<h3>$titre</h3>
	    </div>
		    <div class=\"modal-body\">
		    <p>$question</p>
	    </div>
	    <div class=\"modal-footer\">
		    <a href=\"#\" aria-hidden=\"true\" data-dismiss=\"modal\" class=\"btn\">Fermer</a>
		    <a href=\"$action\" class=\"btn btn-primary\">$rep</a>
		    
	    </div>
    </div>";
	}
	
	/*
	 * @param $datetime La donnée datetime de la base
	* @param $ret ALL : date et heure, DATE : seulement date, TIME : seulement heure
	* @param $dateformat défaut : Séparateur de la date, MO_LETTERS : le mois en lettres
	* @param $timeformat Séparateur de l'heure
	*/
	function sqlDatetimeToFrench($datetime, $ret = "ALL", $dateformat = "/", $timeformat = ":") {
	
	  $months = array("janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
	
	  $date = "";
	  $time = "";
	  if(strpos($datetime, " ")) {
	    list($date,$time) = explode(" ", $datetime);
	  }
	  else {
	    if(strpos($datetime, "-"))
	      $date = $datetime;
	    else if(strpos($datetime, ":"))
	      $time = $datetime;
	  }
	  if(!empty($date))
	    list($year,$month,$day) = explode("-", $date);
	  if(!empty($time))
	    list($hour,$minute,$second) = explode(":", $time);
	
	  if($ret != "TIME") {
  	  if($dateformat == "MO_LETTERS")
  	    $date = $day." ".$months[(int)$month]." ".$year;
  	  else
  	    $date = $day.$dateformat.$month.$dateformat.$year;
  	}
	
	  if($ret != "DATE")
	    $time = $hour.$timeformat.$minute.$timeformat.$second;
	
	  if($ret == "ALL")
	    return array($date,$time);
	  else if($ret == "DATE")
	    return $date;
	  else if($ret == "TIME")
	    return $time;
	  else
	    return false;
	}
	
	
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/templates');
	$twig = new Twig_Environment($loader, array('cache' => false));
	$twig->addFilter("cut", new Twig_Filter_Function("tronque"));

	// Fonction de redirection d'URL : renvoit l'URL sauvegardée précédemment (avec &redirect=true)
	$twig->addFunction("redirectURL", new Twig_Function_Function("redirectURL"));
	
	// Fonctions de construction d'URL
	$twig->addFunction("root", new Twig_Function_Function("root"));
	$twig->addFunction("templates", new Twig_Function_Function("templates"));
	$twig->addFunction("css", new Twig_Function_Function("css"));
	$twig->addFunction("images", new Twig_Function_Function("images"));
	$twig->addFunction("bootstrap", new Twig_Function_Function("bootstrap"));
	$twig->addFunction("fontAwesome", new Twig_Function_Function("fontAwesome"));
	$twig->addFunction("queries", new Twig_Function_Function("queries"));
	$twig->addFunction("PROD_MODE", new Twig_Function_Function("PROD_MODE"));
	$twig->addFunction("isLogged", new Twig_Function_Function("isLogged"));
	$twig->addFunction("isAdmin", new Twig_Function_Function("isAdmin"));
	$twig->addFunction("loggedMember", new Twig_Function_Function("loggedMember"));
	$twig->addFunction("modal", new Twig_Function_Function("modal"));
	$twig->addFunction("participate", new Twig_Function_Function("participate"));
	$twig->addFunction("isCreator", new Twig_Function_Function("isCreator"));
	$twig->addFunction("isMbPlus", new Twig_Function_Function("isMbPlus"));
	$twig->addFunction("isMb", new Twig_Function_Function("isMb"));
	$twig->addFunction("sqlDatetimeToFrench", new Twig_Function_Function("sqlDatetimeToFrench"));