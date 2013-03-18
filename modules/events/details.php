<?php

$errors = array();
if(isset($_GET['eventid'])) {
  $id = $_GET['eventid'];
}
else {
  $errors[] = "Identifiant d'event incorrect";
}
$bdd = new BDD();

$event = $bdd->select("SELECT e.eventname, e.grp, e.creator, e.category, e.date, e.time, m.membfirstname, m.memblastname FROM Events e, Members m where e.creator=m.membid AND e.eventid=".$id.";");
if(!$event)
	$errors[] = $bdd->getLastError();
else
  $event = $event[0];
 
$bdd->close();

echo $twig->render("events_details.html", array("event" => $event, "errors" => $errors));