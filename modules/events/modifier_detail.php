<?php
$bdd = new BDD();
$error = array();
$values = array("eventname"=>"","category"=>"","date"=>"","time"=>"","valider"=>"", "eventid"=>$_GET['idevent']);

//Recuperation des informations de l'evenement
$event =  $bdd->select("SELECT e.eventname, c.catlabel, e.date, e.time, e.grp, e.category from Events e, Categories c where e.category=c.catid AND eventid='".$_GET['idevent']."'");
if(!$event)
	$error[] = $bdd->getLastError();

//SELECT des différentes catégories
$cat = $bdd->select("SELECT c.catlabel, c.catid, g.nbcat from Categories c, Groups g where c.grp=g.grpid AND g.grpid='".$event[0]['grp']."'");

// Enregistrement des modifications
if( isset($_POST["bp_valider"]) && $_POST["bp_valider"] == "true") {
	
	if(isset($_POST["eventname"]) && !empty($_POST["eventname"]))
		$values["eventname"] = $_POST["eventname"];
	else
		$error[] = "Vous devez entrer un nom d'evenement";
	
	if(isset($_POST["catlabel"]) && !empty($_POST["catlabel"]))
		$values["catlabel"] = $_POST["catlabel"];
	else
		$error[] = "Vous devez entrer une catégorie";
	
	if(isset($_POST["date"]) && !empty($_POST["date"]))
		$values["date"] = $_POST["date"];
	else
		$error[] = "Vous devez entrer une date"; 
	
	if(isset($_POST["time"]) && !empty($_POST["time"]))
		$values["time"] = $_POST["time"];
	else
		$error[] = "Vous devez entrer une heure";
		
	if(empty($error)) {
		$result = $bdd->update("Events", array( "eventname" => $values['eventname'], "category" =>  $event[0]['category'], "date" => $values['date'], "time" => $values['time']), array( "eventid" => $values['eventid']));

		if(!$result)
			$error[] = "Erreur de mise à jour de l'evenement : ".$bdd->getLastError();
		$values["valider"] = "1";
	}
}
$bdd->close();

echo $twig->render("event_modifier_detail.html", array("infosEv" => $event[0], "cat"=> $cat[0], "values" => $values));
