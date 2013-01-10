<?php

$bdd = new BDD();

$error = "";

$id=$_GET['id'];
$idG = $_GET['idG'];

$bdd->delete("own", array("member"=>$id, "grp"=>$idG ));


echo $twig->render("groupes_supprimer_membre.html", array("id" => $idG));