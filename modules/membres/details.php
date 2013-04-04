<?php

$errors = array();
$bdd = new BDD();

if(isset($_GET["membid"]) && $bdd->exists("Members","membid",$_GET["membid"])) {
  $membid = $_GET["membid"];
    
  // Récupérer les autres infos du membre ici
  $membre = $bdd->select("select membid, membmail, membfirstname, memblastname from Members
  		where membid = $membid;");
  
  if(!$membre)
  	$error[] = $bdd->getLastError();
  
  //Requête permettant de récupérer les évènents du membre
  $membevents = $bdd->select("select e.eventid, e.eventname, e.date, e.time, g.grpid, g.grpname 
                              from Events as e, Participate as p, Groups as g 
                              where p.member = ".$membid." and 
                                e.eventid = p.event and 
                                g.grpid = e.grp 
                              order by e.date ASC;");
  if(!$membevents)
    $membevents = array();
    
  $bdd->close();
  echo $twig->render("membres_details.html", array("membre" => $membre[0], "membevents" => $membevents));
}
else {
  $bdd->close();
  header("Location:".queries("", "", array()));
}