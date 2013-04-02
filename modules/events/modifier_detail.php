<?php
$bdd = new BDD();
if($bdd->exists('Events', 'eventid',$_GET['idevent'] )){
	$values = array("eventname"=>"","category"=>"","date"=>"","hours"=>"", "minutes"=>"", "eventid"=>$_GET['idevent']);
}else{
	$error[] = "Cet evenement n'éxiste pas dans la base.";
}
if(isset($_POST["error"]) && !empty($_POST["error"]))
	$error = $_POST["error"];
else
	$error = array();

if(isset($_GET["success"]))
	$success = $_GET["success"];
else
	$success = false;

//Recuperation des informations de l'evenement
$event =  $bdd->select("SELECT eventid, eventname, date, time, grp, category from Events where eventid='".$_GET['idevent']."'");
if(!$event)
	$error[] = $bdd->getLastError();

//SELECT des différentes catégories
$cat = $bdd->select("select catid, catlabel from Categories where grp = '".$event[0]['grp']."';");
if(!$cat)
	$error[] = $bdd->getLastError();

//Date
list($year, $month, $day) = explode('-', $event[0]["date"]);
$timestamp = mktime(0,0,0,$month, $day, $year);
setlocale(LC_TIME, "fr_FR");
$event[0]["date"] = strftime("%d/%m/%y", $timestamp);

//Heure
// echo("<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>");
list($heure, $minutes, $sec) = explode(':', $event[0]['time']);
$values['heure']=$heure;
$values['minutes']=$minutes;

// $timestamp=mktime($heure, $minutes, 0, 0, 0, 0);


// Enregistrement des modifications
if( isset($_POST["bp_valider"]) && $_POST["bp_valider"] == "true") {
	
	if(isset($_POST["eventname"]) && !empty($_POST["eventname"]))
		$values["eventname"] = $_POST["eventname"];
	else
		$error[] = "Vous devez entrer un nom d'evenement";
	
	$values["category"] = $_POST["category"];

      if(!isset($_POST["hours"]) || !isset($_POST["minutes"]))
        $errors[] = "Veuillez spécifier une heure";
      else
       $values["hours"] = $_POST["hours"];$values["minutes"] = $_POST["minutes"];
	
	$values["date"]=$_POST['eventdate'];
	
		
	if(empty($error)) {
		// Conversion de la date et de l'heure en format MySQL
		list($day, $month, $year) = explode('/', $values["date"]);
		$timestamp = mktime($values["hours"], $values["minutes"], 0, $month, $day, $year);
		$mysqldate = date("Y-m-d", $timestamp);
		$mysqltime = date("H:i:s", $timestamp);
		
		// Controle si l'heure donnée n'est pas antérieure à celle actuelle
		if($timestamp < time()) {
			$errors[] = "Veuillez entrez une horaire antérieure à l'heure actuelle";
		}
		else {
		//===============Insertion===============\\
		$result = $bdd->update("Events", array( "eventname" => $values['eventname'], "category" =>  $values['category'], "date" => $mysqldate, "time" =>$mysqltime), array( "eventid" => $values['eventid']));

		if(!$result)
			$error[] = "Erreur de mise à jour de l'evenement : ".$bdd->getLastError();
		else
			$success = true;
		
		$_POST["error"] = $error;
		header("Location:".queries("events", "modifier_detail", array("idevent" => $_GET['idevent'], "success" => $success )));
		}
		}
}
$bdd->close();

echo $twig->render("event_modifier_detail.html", array("infosEv" => $event[0], "categories"=> $cat, "values" => $values, "error" => $error, "success" => $success));
