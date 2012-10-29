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

	// Renvoit la racine du site (pour la construction d'URL)
	function root()
	{
		return "http://".$_SERVER['SERVER_NAME'];
	}
	
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/templates');
	$twig = new Twig_Environment($loader, array('cache' => false));
	$twig->addFilter("cut", new Twig_Filter_Function("tronque"));

	// Fonction de redirection d'URL : renvoit l'URL sauvegardée précédemment (avec &redirect=true)
	$twig->addFunction("redirectURL", new Twig_Function_Function("redirectURL"));

	$twig->addFunction("root", new Twig_Function_Function("root"));