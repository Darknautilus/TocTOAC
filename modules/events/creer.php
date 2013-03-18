<?php

$bdd = new BDD();
$values = array();
$errors = array();

if(isset($_GET["grpid"]))
  $grpid = $_GET["grpid"];
else
  $grpid = null;

if($bdd->exists("Groups", "grpid", $grpid)) {
  if(isLogged() && isMb($grpid)) {
    
    if(isset($_POST["filled"])) {
      // Traitement du formulaire
      if(!isset($_POST["eventname"]) || empty($_POST["eventname"]))
        $errors[] = "Veuillez entrer un nom d'événement";
      else
       $values["eventname"] = $_POST["eventname"];
      
      $values["eventdate"] = $_POST["eventdate"];
      
      if(!isset($_POST["hours"]) || !isset($_POST["minutes"]))
        $errors[] = "Veuillez spécifier une heure";
      else
       $values["hours"] = $_POST["hours"];$values["minutes"] = $_POST["minutes"];
      
      $values["category"] = $_POST["category"];
      
      if(empty($errors)) {
        // Conversion de la date et de l'heure en format MySQL
        list($day, $month, $year) = explode('/', $values["eventdate"]);
        $timestamp = mktime($values["hours"], $values["minutes"], 0, $month, $day, $year);
        // Controle si l'heure donnée n'est pas antérieure à celle actuelle
        if($timestamp < time()) {
          $errors[] = "Veuillez entrez une horaire antérieure à l'heure actuelle";
        }
        else {
          $mysqldate = date("Y-m-d", $timestamp);
          $mysqltime = date("H:i:s", $timestamp);
          
          if($values["category"] == 0)
            $category = null;
          else
           $category = $values["category"];
          
          $member = loggedMember();
          $bdd->select("COMMIT;");
          $eventid = $bdd->insert("Events", array("eventname" => $values["eventname"], "grp" => $grpid, "creator" => $member["id"], "category" => $category, "date" => $mysqldate, "time" => $mysqltime));
          if(!$eventid) {
            $errors[] = "Erreur insertion : Events";
          }
          else {
            $participation = $bdd->insert("Participate", array("event" => $eventid, "member" => $member["id"]));
            if(!$participation)
              $errors[] = "Erreur insertion : Participate";
          }
          if(!empty($errors)) {
            $bdd->select("ROLLBACK;");
          }
          else {
            header("Location:".queries("events", "details", array("eventid" => $eventid)));
          }
        }
      }
    }
    else {
      // Récupération de la date du jour
      setlocale(LC_TIME, "fr_FR");
      $values["eventdate"] = strftime("%d/%m/%y");
    }
    
    // Récupération des catégories du groupe
    $categories = $bdd->select("select catid, catlabel from Categories where grp = ".$grpid.";");
    if(!$categories)
      $categories = array();
    array_unshift($categories, array("catid" => 0, "catlabel" => "Aucune catégorie"));
    
    echo $twig->render("events_creer.html", array("values" => $values, "errors" => $errors, "grpid" => $grpid, "categories" => $categories));
      
  }
  else {
    header("Location:".queries("groupes","details", array("idGroupe" => $_GET["grpid"])));
  }
}
else {
  header("Location:".queries("","",array()));
}
$bdd->close();