<?php
//$id = $_GET['idEvent'];
$datas = new BDD();
$error = "";

$events = $datas->select("SELECT e.eventname, e.grp, e.creator, e.category, e.date, e.time, m.membfirstname FROM Events e, members m where e.creator=m.membid AND eventid= '".$_GET["idEvent"]."'");

if (!$events)
	$error .= $datas->getLastError();




if (!$events)
	$error .= $datas->getLastError();



echo $twig->render("event_detail.html", array("events" => $events));