<?php
 
$datas = new BDD();
$error = "";

if(!isset($_GET["confirm"])) {
  echo $twig->render("global_confirm.html",
      array("titre" => "Etes-vous sûr de vouloir supprimer cet évévement ?", "reponseN" => "Non", "reponseP" => "Oui",
          "urlP" => queries("events", "supprimer", array("confirm" => true, "eventid" => $_GET["eventid"]))));
}
else {
  
  $event = $datas->select("select grp from Events where eventid = ".$_GET["eventid"].";");
  
  $participate = $datas->delete("Participate", array("event"=>$_GET['eventid'] ));
  $events = $datas->delete("Events", array("eventid"=>$_GET['eventid'] ));
  
  if(empty($event[0]["grp"])) {
    header("Location:".queries("events","",array()));
  }
  else {
    header("Location:".queries("groupes","details", array("idGroupe" => $event[0]["grp"])));
  }
}
$datas->close();