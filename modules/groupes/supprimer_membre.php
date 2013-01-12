<?php

$bdd = new BDD();

$error = "";

$id=$_GET['id'];
$idG = $_GET['idG'];

$bdd->delete("Own", array("member"=>$id, "grp"=>$idG ));

$bdd->close();

echo $twig->render("groupes_supprimer_membre.html", array("id" => $idG));