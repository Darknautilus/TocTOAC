<?php

$bdd = new BDD();
$errors = array();

if(isset($_GET['eventid']) && $bdd->exists("Events", "eventid", $_GET["eventid"])) {
  $id = $_GET['eventid'];
}
else {
  $bdd->close();
  header("Location:".queries("","",array()));
}

$event = $bdd->select("SELECT e.eventid, e.eventname, e.grp, e.creator, e.category, e.date, e.time, e.creator, m.membfirstname, m.memblastname FROM Events e, Members m where e.creator=m.membid AND e.eventid=".$id.";");
if(!$event)
	$errors[] = $bdd->getLastError();
else
  $event = $event[0];
 
$bdd->close();

echo $twig->render("events_details.html", array("event" => $event, "errors" => $errors));