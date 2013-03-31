<?php

$errors = array();
$success = false;
$groupe = array();

$bdd= new BDD();
if(isset($_GET["grpid"]) && $bdd->exists("Groups", "grpid", $_GET["grpid"])) {
  $groupe["grpid"] = $_GET["grpid"];
}
else if(isset($_POST["filled"])) {
	$id= $_POST['grpid'];

	$groupe = $bdd->select("Select grpid, grpname, visibility, description from Groups
						where grpid = ".$id.";");
	if(!$groupe) {
		$errors[] = "Groupe inconnu";
	}
	else {
	  $groupe = $groupe[0];
	}
			
	// Vérification saisie e-mail
	if(isset($_POST["email"]) && !empty($_POST["email"])) {
		$adresseDest = $_POST["email"];
		
		// Objet du mail.
		$objet = 'Invitation au groupe : '.$groupe['grpname'].'.';
		// Message HTML
		$message = '<p>Bonjour,<br /><br />
			   Vous êtes invité à rejoindre le groupe '.$groupe['grpname'].'.<br />
			   Vous pouvez accéder au site <a href="toctoac">TocTOAC</a><br /><br />
			   Bonne Journée <br /></p>'."\n";
		
		// Envoi
		envoyerMail($adresseDest,$objet,$message);
		$success = "Invitation envoyée";
	}
	else {
		$errors[] = "Vous devez saisir une adresse mail.";
	}
	$bdd->close();
}
else {
  header("Location:".queries("","",array()));
}

echo $twig->render("groupe_inviter_membre.html", array("groupe" => $groupe,"errors" => $errors, "success" => $success));