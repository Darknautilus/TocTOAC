<?php
$id = $_GET['idEvent'];
$datas = new BDD();
$error = "";

$events = $datas->select("SELECT e.eventname, e.grp, e.creator, e.category, e.date, e.time, m.membfirstname FROM Events e, members m where e.creator=m.membid AND e.eventid=".$id.";");

if (!$events)
	$error .= $datas->getLastError();

$datas->close();

echo $twig->render("events_details.html", array("events" => $events));