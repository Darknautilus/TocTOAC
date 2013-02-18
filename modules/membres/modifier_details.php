<?php

$errors = array();

if(isset($_GET["membid"])) {
	$bdd = new BDD();
	$membre = $bdd->select("select membid from Members where membid = ".$_GET["membid"].";");
	if(!$membre) {
		$errors[] = "Id inconnu";
	}

	if(empty($errors)) {
		$membid = $membre[0]["membid"];

		// Récupérer les autres infos du membre ici
		$membre = $bdd->select("select membid, membmail, membfirstname, memblastname, membpasswd from Members
								where membid = $membid;");

		if(!$membre)
			$errors[] = $bdd->getLastError();
		
		// Enregistrement des modifications (Prénom / Nom / @mail)
		if( isset($_POST["filled"]) && $_POST["filled"] == "true") {
		
			// Vérification saisie prénom
			if(isset($_POST["prenom"]) && !empty($_POST["prenom"]))
				$values["membPrenom"] = $_POST["prenom"];
			else
				$errors[] = "Vous devez entrer votre prénom.";
			
			// Vérification saisie nom
			if(isset($_POST["nom"]) && !empty($_POST["nom"]))
				$values["membNom"] = $_POST["nom"];
			else
				$errors[] = "Vous devez entrer votre nom.";			
			
		  	// Vérification saisie adresse email
		  	if(isset($_POST["email"]) && !empty($_POST["email"]))
		  		$values["email"] = $_POST["email"];
		  	else
		  		$errors[] = "Vous devez donner une adresse e-mail";
			
		  	if(empty($errors)){
		  		$email=$_POST['email'];
		  		$exist=$bdd->select("Select membid From Members where membmail='\"$email\"' and membid != '$membid';");
		  	
		  		if($exist)
		  			$errors[] = "Ce mail est déjà utilisé";
		  	}
		  	
		
			if(empty($errors)) {
				// Update dans la base de donnée
				$result = $bdd->update("Members", array( "membmail" => $_POST["email"], "membfirstname" => $_POST["prenom"], "memblastname" => $_POST["nom"]), array( "membid" => $membid));
					
				if(!$result) {
					$errors[] = "Erreur update des informations du membre : ".$bdd->getLastError();
				}
				else {
					header("Location:index.php");
				}
			}
		}
		
		// Enregistrement des modifications du mot de passe
		if( isset($_POST["filled2"]) && $_POST["filled2"] == "true") {
			// Vérification saisie ancien mot de passe
			if(!empty($_POST["oldMotDePasse"])){
				$values["oldMotDePasse"] = $_POST["oldMotDePasse"];
			
				if(!check_password($values["oldMotDePasse"], $membre[0]["membpasswd"])){
					$errors[] = "Le mot de passe saisi ne correspond pas à l'ancien mot de passe.";
				}
			
				// Vérification saisie nouveau mot de passe
				if(isset($_POST["newMotDePasse"]) && !empty($_POST["newMotDePasse"])) {
					if(!isset($_POST["verifNewMotDePasse"]) && $_POST["newMotDePasse"] != $_POST["verifNewMotDePasse"])
						$errors[] = "Les mots de passe doivent être identiques";
				}
				else {
					$errors[] = "Vous devez définir un mot de passe";
				}
			}
			
			// Update dans la base de donnée
			if(empty($errors)) {
				$hash = hash_password($_POST["newMotDePasse"]);
				$result = $bdd->update("Members", array("membpasswd" => $hash), array( "membid" => $membid));
					
				if(!$result) {
					$errors[] = "Erreur update des informations du membre : ".$bdd->getLastError();
				}
				else {
					header("Location:index.php");
				}
			}
		}
	$bdd->close();
	echo $twig->render("membres_modifier_details.html", array("membid" => $membid, "infosMemb" => $membre[0]));
	}
}
else {
	$errors[] = "Id non spécifié";
}

var_dump($membre);
if(!empty($errors)) {
	echo $twig->render("index_show.html", array("errors" => $errors));
}