<?php

$errors = array();

if(isset($_GET['idGroupe'])) {
	$id= $_GET['idGroupe'];

	$bdd=new BDD();
	$groupe = $bdd->select("Select grpid, grpname, visibility, description from Groups
						where grpid = $id ;");
	if(!$groupe) {
		$errors[] = "Id inconnu";
	}
	
	if( isset($_POST["invit"]) && $_POST["invit"] == "true") {
		
		// Vérification saisie e-mail
		if(isset($_POST["email"]) && !empty($_POST["email"])) {
			$adresseDest = $_POST["email"];
			
			// Objet du mail.
			$objet = 'Invitation au groupe : '.$groupe[0]['grpname'].'.';
			// var_dump($objet);
			
			// Message HTML
			
			$message = '<p>Bonjour,<br /><br />
				   Vous êtes invité à rejoindre le groupe '.$groupe[0]['grpname'].'.<br />
				   Vous pouvez accéder au site <a href="toctoac">TocTOAC</a><br /><br />
				   Bonne Journée <br /></p>'."\n";
			// var_dump($message);
			
			// Envoi
			envoyerMail($adresseDest,$objet,$message);
		}
		else {
			$errors[] = "Vous devez saisir une adresse mail.";
		}
	}

	$bdd->close();
	echo $twig->render("groupe_inviter_membre.html", array("groupe" => $groupe));
}
else {
	$errors[] = "Id non spécifié";
}
if(!empty($errors)) {
	echo $twig->render("index_show.html", array("errors" => $errors));
}