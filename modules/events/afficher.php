<?php

$datas = new BDD();
$error = "";

$events = $datas->select("SELECT eventId, grp, date FROM events");

if (!events)
	$error .= $datas->getLastError();





echo $twig->render("events_afficher.html", array("event" => $events));

