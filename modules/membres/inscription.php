<?php

if(!isLogged()) {

  $error = array();
  $values = array("prenom"=>"","nom"=>"","email"=>"");
  
  if(isset($_POST["filled"]) && $_POST["filled"] == "true") {
  	
  	$bdd = new BDD();
  	
  	if(isset($_POST["prenom"]) && !empty($_POST["prenom"]))
  		$values["prenom"] = $_POST["prenom"];
  	else
  		$error[] = "Vous devez donner un prénom";
  
  	if(isset($_POST["nom"]) && !empty($_POST["nom"]))
  		$values["nom"] = $_POST["nom"];
  	else
  		$error[] = "Vous devez donner un nom";
  
  	if(isset($_POST["email"]) && !empty($_POST["email"]))
  		$values["email"] = $_POST["email"];
  	else
  		$error[] = "Vous devez donner une adresse e-mail";
  
  	if(isset($_POST["motDePasse"]) && !empty($_POST["motDePasse"])) {
  		if(!isset($_POST["verifMotDePasse"]) || $_POST["motDePasse"] != $_POST["verifMotDePasse"])
  			$error[] = "Les mots de passe doivent être identiques";
  	}
  	else {
  		$error[] = "Vous devez définir un mot de passe";
  	}
  	
  	if(empty($error)){
  		$email=$_POST['email'];
  		$exist=$bdd->select("Select membid From Members where membmail=\"$email\";");
  
  		if($exist)
  			$error[] = "Ce mail est déjà utilisé";
  	}
  	
  	if(empty($error)) {
  		//Insertion du nouveau membre dans la table membre
  		$hash = hash_password($_POST["motDePasse"]);
  		$result = $bdd->insert("Members", array("membmail" => $_POST["email"], "membfirstname" => $_POST["prenom"], "memblastname" => $_POST["nom"], "membpasswd" => $hash, "admin" => false));
  		
  		if(!$result) {
  			$error[] = "Erreur insertion : ".$bdd->getLastError();
  		}
  		else {
  			header("Location:index.php");
  		}
  	}
  
  	$bdd->close();
  }
  
  echo $twig->render("membres_inscription.html", array("errors" => $error, "values" => $values));
}
else {
  echo $twig->render("index_show.html", array());
}