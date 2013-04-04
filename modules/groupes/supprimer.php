<?php

$bdd = new BDD();

if(!isset($_GET["confirm"])) {
  echo $twig->render("global_confirm.html",
      array("titre" => "Etes-vous sûr de vouloir supprimer ce groupe ainsi que tous les événements et catégories s'y rapportant ?", "reponseN" => "Non", "reponseP" => "Oui",
          "urlP" => queries("groupes", "supprimer", array("confirm" => true, "grpid" => $_GET["grpid"]))));
}
else {
  if(isLogged() && isset($_GET["grpid"]) && $bdd->exists("Groups","grpid",$_GET["grpid"]) && isMbPlus($_GET["grpid"])) {
    $idGroup = $_GET["grpid"];
    
    // Suppression des évènements liés au groupe
    $result = $bdd->delete("Events", array( "grp" => $idGroup));
    if(!$result)
      $error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
    // Suppression des catégories liés au groupe
    $result = $bdd->delete("Categories", array( "grp" => $idGroup));
    if(!$result)
      $error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
    // Suppression de l'appartenance au goupe
    $result = $bdd->delete("Own", array( "grp" => $idGroup));
    if(!$result)
      $error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
    // Suppression du groupe
    $result = $bdd->delete("Groups", array( "grpid" => $idGroup));
    if(!$result)
      $error[] = "Erreur de suppression du groupe : ".$bdd->getLastError();
    
    majGrpMb();
    majGrpMbPlus();
    
    header("Location:".queries("groupes","mesgroupes",array()));
  }
  else {
    $bdd->close();
    header("Location:".queries("", "", array()));
  }
}