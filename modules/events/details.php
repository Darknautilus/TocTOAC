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

$event = $bdd->select("SELECT e.eventid, e.eventname, e.grp, g.grpname, e.creator, e.category, e.date, e.time, e.creator, m.membfirstname, m.memblastname FROM Events e, Members m, Groups g  where g.grpid = e.grp AND e.creator=m.membid AND e.eventid=".$id.";");
$members = $bdd->select("select membid,membfirstname, memblastname from Members m, Participate p where m.membid = p.member and p.event = ".$id.";");
if(!$event)
	$errors[] = $bdd->getLastError();
else
  $event = $event[0];
 
$bdd->close();

echo $twig->render("events_details.html", array("event" => $event, "members" => $members, "errors" => $errors));