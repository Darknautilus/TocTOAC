<?php

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
			if($membre[0]["membpasswd"] != $_POST["mdp"]) {
				$errors[] = "Le mot de passe est incorrect";
			}
		}
	}
	// On définit les variables de session
	if(empty($errors)) {
		foreach($membre[0] as $key => $value) {
			$_SESSION[$key] = $value;
		}
		$_SESSION["logged"] = true;
		
		// On redirige
		header("Location:".queries("", "", array()));
	}
}

echo $twig->render("membres_connexion.html", array("values" => $values, "errors" =>  $errors));