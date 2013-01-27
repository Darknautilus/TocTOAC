<?php

/*
	Module affiché par défaut
*/
define("DEFAULT_MODULE", "index");

/*
	Action par défaut pour chaque module
	array(module => action)
*/
$DEFAULT_ACTION = array(
	"index" => "show",
	"membres" => "auth",
	"test" => "home",
	"groupes" => "afficher",
	"base" => "afficher"
);

$GLOBALS["DEFAULT_ACTION"] = $DEFAULT_ACTION;

/*
	Liste des modules et des actions
	array(module => array(action [,action...]) )
*/
$MODULES = array(
	"index" => array(
		"show",
		"about"
		),
	"membres" => array(
		"connexion",
		"deconnexion",
		"desinscription",
		"inscription",
		"auth",
	  "details"
		),
	"groupes" => array(
		"afficher",
		"details",
		"creer",
		"supprimer_membre",
		"creerCat",
		"afficher_membre",
		"rejoindre",
		"mesgroupes"
		),
	"test" => array(
		"home",
		"bdd.test",
		"security"
		),
	"events" => array(
		"afficher",
		"event_detail"
		),
	"base" => array(
		"afficher",
		"raz",
		)
	);

$GLOBALS["MODULES"] = $MODULES;

/*
	Fichiers de configuration des modules
	array(module => nom_fichier)
*/
$MODULES_CONFIG = array(
	"base" => "config",
	"membres" => "config"
);

$GLOBALS["MODULES_CONFIG"] = $MODULES_CONFIG;

// ===================== -v- Ne pas toucher -v- ==================

/*
	Fonction de controles des modules et actions
*/
function is_module($pModule)
{
	foreach($GLOBALS["MODULES"] as $module => $action)
	{
		if($pModule == $module)
			return true;
	}
	
	return false;
}

function is_action($pModule, $pAction)
{
	foreach($GLOBALS["MODULES"] as $module => $tabAction)
	{
		if($pModule == $module)
		{
			foreach($tabAction as $action)
			{
				if($action == $pAction)
					return true;
			}
		}
	}
	
	return false;
}

function default_module()
{
	return DEFAULT_MODULE;
}

function default_action($pModule)
{
	return $GLOBALS["DEFAULT_ACTION"][$pModule];
}

function is_config($pModule)
{
	foreach($GLOBALS["MODULES_CONFIG"] as $module => $config)
	{
		if($module == $pModule && $config != null)
			return true;
	}
	
	return false;
}

function configFile($pModule)
{
	return $GLOBALS["MODULES_CONFIG"][$pModule];
}