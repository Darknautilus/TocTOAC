<?php

$bdd = new BDD();
$errors = array();

if(isset($_GET["grpid"]) && isLogged() && $bdd->exists("Groups", "grpid", $_GET["grpid"]) && isMb($_GET["grpid"])) {
  $member = loggedMember();
  
  // S'il s'agit du seul membre+, on lui demande d'en désigner un
  if(isMbPlus($_GET["grpid"]) && count(getMbPlus($_GET["grpid"])) == 1) {
    $errors[] = "Vous devez d'abord désigner un autre Membre+";
  }
  
  if(empty($errors)) {
    $suppr = $bdd->delete("Own", array("grp" => $_GET["grpid"], "member" => $member["id"]));
    if(isMbPlus($_GET["grpid"]))
      majGrpMbPlus();
    majGrpMb();
    if(!$suppr) {
      $errors[] = "Erreur suppression lien membre-groupe";
    }
  }
  $bdd->close();
  if(empty($errors)) {
    header("Location:".queries("groupes", "details", array("idGroupe" => $_GET["grpid"])));
  }
  else {
    echo $twig->render("global_error.html", array("errors" => $errors, "titre" => "Départ d'un groupe"));
  }
}
else {
  $bdd->close();
  header("Location:".queries("", "", array()));
}