<?php

echo $twig->render("membres_inscription.html", array("message" => "It works" ));

# On regarde si tous les champs ont été renseignés
if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['motDePasse']) && isset($_POST['verifMotDePasse']) && isset($_POST['adresseMail']))
{
	# Les mots de passe correspondent-ils ?
	if($_POST['motDePasse'] == $_POST['verifMotDePasse'])
	{
		//Insertion du nouveau membre dans la table membre
		insert(Members, "membMail => ".$_POST['adresseMail'], "membFirstName => ".$_POST['prenom'], "membLastName => ".$_POST['nom'], "membPasswd => ".$_POST['motDePasse']);
	}
	else
	{
		die("Les mots de passe ne correspondent pas...");
	}
}
# Sinon on affiche un message d'erreur
	else
	{
		die("Tous les champs doivent etre remplis pour valider votre inscription");
	}
?>