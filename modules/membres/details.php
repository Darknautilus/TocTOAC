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
    $membre = $bdd->select("select membid, membmail, membfirstname, memblastname from Members
    		where membid = $membid;");
    
    if(!$membre)
    	$error[] = $bdd->getLastError();
    
    //Requête permettant de récupérer les évènents du membre
<<<<<<< HEAD
    $bdd2 = new BDD();
    $membevents = $bdd2->select("select p.event, p.member, e.eventid, e.eventname, e.grp, e.date, e.time, g.grpname, m.membid, m.membfirstname, m.memblastname
    							from participate as p, events as e, groups as g, members as m
    							where p.event = e.eventid
    							and e.grp = g.grpid
    							and p.member = m.membid;");
=======
    $membevents = $bdd->select("select p.event, p.member, e.eventid, e.eventname, e.grp, e.date, e.time, g.grpname 
    							from Participate as p, Events as e, Groups as g
    							where p.event = e.eventid
    							and e.grp = g.grpid
                  and p.member = ".$membid.";");
    
    if(!$membevents)
      $membevents = array();
>>>>>>> 2504556db620577b538b27aa3a8b18a101619af7
    
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
