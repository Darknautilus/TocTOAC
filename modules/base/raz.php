<?php

$errors[] = array();

$bdd = new BDD();
$bdd->select("COMMIT;");

$fichier = file_get_contents(root()."/Docs/base.sql"); // on charge le fichier SQL

try {
	$bdd->getBDD()->exec($fichier);
}
catch (PDOException $e) {
	$errors[] = "Erreur de lecture du fichier : ".$e->getMessage();
	$bdd->select("ROLLBACK;");
}
 
$bdd->close();

echo $twig->render("base_raz.html", array());