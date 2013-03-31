<?php

$bdd = new BDD();
$errors = array();

if(isset($_GET["idevent"]) && isLogged() && $bdd->exists("Events", "eventid", $_GET["idevent"]) && participate($_GET["idevent"])) {
  $member = loggedMember();
  $suppr = $bdd->delete("Participate", array("event" => $_GET["idevent"], "member" => $member["id"]));
  if(!$suppr) {
    $errors[] = "Erreur suppression participation";
  }
  $bdd->close();
  header("Location:".queries("events", "details", array("eventid" => $_GET["idevent"])));
}
else {
  $bdd->close();
  header("Location:".queries("", "", array()));
}