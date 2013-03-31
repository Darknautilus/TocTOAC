<?php

$bdd = new BDD();
$errors = array();

// Vérification de l'existence de l'event
if(isset($_GET["idevent"]) && isLogged() && $bdd->exists("Events", "eventid", $_GET["idevent"])) {
  $event = $bdd->select("select eventid,grp from Events where eventid = ".$_GET["idevent"].";");
  $event = $event[0];
  
  // Vérifie si le membre ne participe pas déjà à l'événement
  if(participate($event)) {
    $errors[] = "Déjà participant";
    header("Location:".queries("groupes", "details", array("idGroupe" => $event["eventid"])));
  }
  else {
    // Si tout OK, insertion de l'event
    $member = loggedMember();
    $bdd->insert("Participate", array("event" => $event["eventid"], "member" => $member["id"]));
    header("Location:".queries("events", "details", array("eventid" => $event["eventid"])));
  }
}
else {
  header("Location:".queries("", "", array()));
}

$bdd->close();