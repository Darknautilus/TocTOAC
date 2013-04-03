<?php

$bdd = new BDD();

$events = $bdd->select("select e.eventid, e.eventname, e.date, e.time, g.grpid, g.grpname from Events e, Groups g where g.grpid = e.grp order by e.date;");
if(!$events)
  $events = array();
$bdd->close();
echo $twig->render("events_afficher.html", array("events" => $events));