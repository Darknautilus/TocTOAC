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
    $bdd2 = new BDD();
    $membevents = $bdd2->select("select p.event, p.member, e.eventid, e.eventname, e.grp, e.date, e.time, g.grpname, m.membid, m.membfirstname, m.memblastname
    							from participate as p, events as e, groups as g, members as m
    							where p.event = e.eventid
    							and e.grp = g.grpid
    							and p.member = m.membid;");
    
    echo $twig->render("membres_details.html", array("membid" => $membid, "membevents" => $membevents));
  }
  $bdd->close();
}
else {
  $errors[] = "Id non spécifié";
}

if(!empty($errors))
    echo $twig->render("index_show.html", array("errors" => $errors));