<?php

$redirect = false;
$errors = array();
$values = array();

// Si l'on a rempli le formulaire et qu'on est pas loggué
if(isset($_POST["filled"]) && !isLogged()) {
	// Controles de surface
	if(empty($_POST["email"])) {
		$errors[] = "Veuillez entrer une adresse email valide.";
	}
	else {
		$values["email"] = $_POST["email"];
		if(empty($_POST["mdp"])) {
			$errors[] = "Veuillez entrer votre mot de passe.";
		}
	}
	
	// Controles en profondeur
	if(empty($errors)) {
		// On teste la présence de l'email dans la base
		$bdd = new BDD();
		$membre = $bdd->select("SELECT membid, membmail, membfirstname, memblastname,membpasswd,admin FROM Members WHERE membmail='".$_POST["email"]."';");
		$bdd->close();
		
		if(!$membre) {
				$errors[] = "Le mail spécifié n'existe pas";
		}
		else {
			// On controle le mot de passe
			if(!check_password($_POST["mdp"], $membre[0]["membpasswd"])) {
				$errors[] = "Le mot de passe est incorrect";
			}
		}
	}
	// On définit les variables de session
	if(empty($errors)) {
	  
		$_SESSION["logged"] = true;
		$_SESSION["membinfos"] = $membre[0];
		$GLOBALS["membinfos"] = $membre[0];
		
		if(isset($_POST["cookie"])) {
		  creerCookie("logged", true);
		  creerCookie("membinfos",$membre[0]);
		}
				
		// On charge les groupes dont le membre est membre		
		majGrpMb();
		
		// On charge les groupes dont le membre est membre+
		majGrpMbPlus();
		
		// On redirige
		//$redirect = true;
		header("Location:".queries("", "", array()));
	}
}

if($redirect)
  echo $twig->render("index_show.html", array());
else
  echo $twig->render("membres_connexion.html", array("values" => $values, "errors" =>  $errors));