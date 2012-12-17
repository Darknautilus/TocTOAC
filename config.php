<?php

/*
	Mode de l'application
	true : mode de développement
	false : mode de production
*/
define("APP_MODE_PROD", false);

/*
	Base de donnees
*/

define("DBSERVER", "");
define("DBNAME", "");
define("DBUSER", "root");
define("DBPASSWD", "root");

define("DBHEADER", 'mysql:host='.DBSERVER.';dbname='.DBNAME);

/*
	Chemins des repertoires
*/

define("PATH_ROOT", dirname(__FILE__));
define("PATH_MODULES", PATH_ROOT."/modules");
define("PATH_MODELES", PATH_ROOT."/modeles");
define("PATH_TEMPLATES", PATH_ROOT."/templates");
