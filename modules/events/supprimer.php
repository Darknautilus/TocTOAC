<?php
 
$datas = new BDD();
$error = "";

$participate = $datas->delete("participate", array("event"=>$_GET['idEvent'] ));
$events = $datas->delete("events", array("eventid"=>$_GET['idEvent'] ));


if (!$events || !$participate)
	$error .= $datas->getLastError();

$datas->close();

echo $twig->render("events_supprimer.html", array("events" => $events));