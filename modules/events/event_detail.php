<?php
//$id = $_GET['idEvent'];
$datas = new BDD();
$error = "";

$events = $datas->select("SELECT * FROM Events where eventid= '".$_GET["idEvent"]."'");

if (!$events)
	$error .= $datas->getLastError();




if (!$events)
	$error .= $datas->getLastError();



echo $twig->render("event_detail.html", array("events" => $events));