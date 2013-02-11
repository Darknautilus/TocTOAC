<?php

$errors = array();

if(isset($_GET["membid"])) {
  $bdd = new BDD();
  $membre = $bdd->select("select membid, membfirstname, memblastname from Members where membid = ".$_GET["membid"].";");
  if(!$membre) {
    $errors[] = "Id inconnu";
  }
  
  if(empty($errors)) {
    $membid = $membre[0]["membid"];
    
    // Récupérer les autres infos du membre ici
    
    //Requête permettant de récupérer les évènents du membre
    $membevents = $bdd->select("select p.event, p.member, e.eventid, e.eventname, e.grp, e.date, e.time, g.grpname 
    							from Participate as p, Events as e, Groups as g
    							where p.event = e.eventid
    							and e.grp = g.grpid
                  and p.member = ".$membid.";");
    
    if(!$membevents)
      $membevents = array();
    
  }
  $bdd->close();
}
else {
  $errors[] = "Id non spécifié";
}

if(empty($errors))
  echo $twig->render("membres_details.html", array("membre" => $membre[0], "membevents" => $membevents));
else
  header("Location:".queries("membres","",array("errors" => $errors)));