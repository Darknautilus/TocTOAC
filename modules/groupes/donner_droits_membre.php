<?php

$bdd = new BDD();

$error = "" ;

$id = $_GET['id'];
$idG = $idG['idG'];

$bdd->update("own", array("member"=>$id, "grnt"=>2), array("member"=>$id, "grnt"=>1));

$bdd->close();

echo $twig->render("groupes_donner_droits_membre.html", array("id"=>$idG));