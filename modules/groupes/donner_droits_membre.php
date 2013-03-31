<?php
$id = $_GET['id'];
$idG = $_GET['idG'];

if(!isset($_GET["confirm"])) {
  echo $twig->render("global_confirm.html",
      array("titre" => "Etes-vous sûr de vouloir accorder les droits de Membre+ à ce membre ?", "reponseN" => "Non", "reponseP" => "Oui",
          "urlP" => queries("groupes", "donner_droits_membre", array("confirm" => true, "id" => $id, "idG" => $idG))));
}
else {
  $bdd = new BDD();
  $error = "" ;
  $bdd->update("Own", array("grnt" => 2), array("member"=>$id, "grp" => $idG));
  $bdd->close();
  echo $twig->render("groupes_donner_droits_membre.html", array("id"=>$idG));
}