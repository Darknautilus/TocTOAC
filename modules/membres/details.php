<?php

$errors = array();

if(isset($_GET["membid"])) {
  $bdd = new BDD();
  $membre = $bdd->select("select membid from Members where membid = ".$_GET["membid"].";");
  if(!$membre) {
    $errors[] = "Id inconnu";
  }
  
  if(empty($errors)) {
    $membid = $membre[0]["membid"];
    
    // Récupérer les autres infos du membre ici
    
    //Requête permettant de récupérer les évènents du membre
    $bdd2 = new BDD();
    $membevents = $bdd2->select("select p.event, p.member, e.eventid, e.eventname, e.grp, e.date, e.time, g.grpname 
    							from participate as p, events as e, groups as g
    							where p.event = e.eventid
    							and e.grp = g.grpid;");
    
    echo $twig->render("membres_details.html", array("membid" => $membid, "membevents" => $membevents));
  }
  $bdd->close();
}
else {
  $errors[] = "Id non spécifié";
}

if(!empty($errors))
    echo $twig->render("index_show.html", array("errors" => $errors));