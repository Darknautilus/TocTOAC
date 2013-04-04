<?php

$bdd= new BDD();
$errors = array();

if(isset($_GET['idGroupe']) && $bdd->exists("Groups", "grpid", $_GET['idGroupe'])) {

  $id= $_GET['idGroupe'];
  
  // Informations groupe
  $groupe = $bdd->select("Select grpid, grpname, visibility, description from Groups
  						where grpid = $id ;");	
  
  // on récupère tous les membres appartenants a un groupe
  $mem = $bdd->select("SELECT m.membid, m.membfirstname, m.memblastname
  						FROM Members AS m, Groups AS g, Own AS o
  						WHERE g.grpid =$id
  						AND o.grp = g.grpid
  						AND o.member = m.membid");
  
  // Nombre de membres
  if(!$mem) {
  	$error = $bdd->getLastError();
  	$nbMemb=0;
  }
  else {
    $nbMemb = count($mem);
  }
  
  // Construction du tableau des events classés par catégorie
  // Récupération des catégories
  $categories = $bdd->select("select catid, catlabel from Categories where grp = ".$id.";");
  if(!$categories) {
    $categories = array();
  }
  
  // Récupération des events pour chaque catégorie
  foreach($categories as &$category) {
    $events = $bdd->select("select e.eventid, e.eventname, e.date, e.time,  m.membfirstname, m.memblastname
  					from Events as e, Members as m
  					where e.grp= ".$id."
  					and e.creator = m.membid
            and e.category = ".$category["catid"].";");
    $category["events"] = $events;
  }
  // Récupération des events sans catégorie
  $eventsSC = $bdd->select("select e.eventid, e.eventname, e.date, e.time,  m.membfirstname, m.memblastname
  					from Events as e, Members as m
  					where e.grp= ".$id." and e.creator = m.membid and e.category = 0");
  // Et ajout dans le tableau
  $categories[] = array("catid" => "all", "catlabel" => "Sans catégorie", "events" => $eventsSC);
  echo $twig->render("groupes_details.html", array("groupe" => $groupe[0], "nbMembres" => $nbMemb  , "categories" => $categories));

}
else {
  header("Location:".queries("","",array()));
}