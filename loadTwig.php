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
		return css()."/images";
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
		return isset($_SESSION["logged"]);
	}
	function isAdmin() {
		return (isset($_SESSION["logged"]) && $_SESSION["admin"]);
	}
	function loggedMember() {
		return array(
				"id" => $_SESSION["membid"],
				"mail" => $_SESSION["membmail"],
				"membfirstname" => $_SESSION["membfirstname"],
				"memblastname" => $_SESSION["memblastname"],
				"membpasswd" => $_SESSION["membpasswd"]
				);
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
	$twig->addFunction("queries", new Twig_Function_Function("queries"));
	$twig->addFunction("PROD_MODE", new Twig_Function_Function("PROD_MODE"));
	$twig->addFunction("isLogged", new Twig_Function_Function("isLogged"));
	$twig->addFunction("isAdmin", new Twig_Function_Function("isAdmin"));
	$twig->addFunction("loggedMember", new Twig_Function_Function("loggedMember"));
	$twig->addFunction("modal", new Twig_Function_Function("modal"));
