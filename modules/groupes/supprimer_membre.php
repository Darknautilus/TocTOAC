<?php

$id=$_GET['id'];
$idG = $_GET['idG'];

if(!isset($_GET["confirm"])) {
  echo $twig->render("global_confirm.html", 
      array("titre" => "Etes-vous sûr de vouloir supprimer ce membre ?", "reponseN" => "Non", "reponseP" => "Oui",
          "urlP" => queries("groupes", "supprimer_membre", array("confirm" => true, "id" => $id, "idG" => $idG))));
}
else {
  $bdd = new BDD();
  $error = "";
  $bdd->delete("Own", array("member"=>$id, "grp"=>$idG ));
  $bdd->close();
  echo $twig->render("groupes_supprimer_membre.html", array("id" => $idG));
}