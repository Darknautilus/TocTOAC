<?php

$errors = array();
$groupes = null;

$bdd = new BDD();

$groupes = $bdd->select("select g.grpid,g.grpname,g.visibility,g.description,g.nbmemb from Groups g;");

if(!groupes)
	$errors[] = "Il n'y a rien à afficher";

$bdd->close();

echo $twig->render("groupes_afficher.html", array("groupes"=>$groupes, "errors"=>$errors));