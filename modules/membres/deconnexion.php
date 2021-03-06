<?php

if(isset($GLOBALS["logged"])) {
	// Détruit toutes les variables de session
	$_SESSION = array();

	// Si vous voulez détruire complètement la session, effacez également
	// le cookie de session.
	// Note : cela détruira la session et pas seulement les données de session !
	if (ini_get("session.use_cookies")) {
    	$params = session_get_cookie_params();
    	setcookie(session_name(), '', time() - 42000,
        	$params["path"], $params["domain"],
        	$params["secure"], $params["httponly"]
    	);
	}

	// On détruit les cookies
	setcookie("logged", "", time()-3600);
	setcookie("membinfos", "", time()-3600);
	setcookie("grpMb", "", time()-3600);
	setcookie("grpMbPlus", "", time()-3600);
	
	// Finalement, on détruit la session.
	session_destroy();
	
	majGlobals();
	
	echo $twig->render("membres_deconnexion.html", array());
}
else {
	header("Location:".queries("", "", ""));
}