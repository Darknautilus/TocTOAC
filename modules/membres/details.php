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
    
    
    echo $twig->render("membres_details.html", array("membid" => $membid));
  }
  $bdd->close();
}
else {
  $errors[] = "Id non spécifié";
}

if(!empty($errors))
    echo $twig->render("index_show.html", array("errors" => $errors));