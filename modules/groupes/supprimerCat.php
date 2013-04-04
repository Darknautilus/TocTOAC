<?php
$error = array();
$bdd = new BDD();

if(!isset($_GET["confirm"])) {
  echo $twig->render("global_confirm.html",
      array("titre" => "Etes-vous sûr de vouloir supprimer cette catégorie ?", "reponseN" => "Non", "reponseP" => "Oui",
          "urlP" => queries("groupes", "supprimerCat", array("confirm" => true, "catid" => $_GET["catid"]))));
}
else {
  if(isLogged() && isset($_GET["catid"]) && $bdd->exists("Categories","catid",$_GET["catid"])) {
  	
    $cat = $bdd->select("select catid, grp from Categories where catid = ".$_GET["catid"].";");
    $cat = $cat[0];
    
    // Modification de la catégorie des événements concernés
    $bdd->update("Events", array("category" => 0), array("category" => $cat["catid"]));

    //Suppression catégorie dans la table Categories
  	$result = $bdd->delete("Categories", array( "catid" => $cat["catid"]));
  	if(!$result)
  		$error[] = "Erreur suppression : ".$bdd->getLastError();
  	
  	$bdd->close();
  	header("Location:".queries("groupes","modifier",array("idGroupe" => $cat["grp"])));
  }
  else {
    $bdd->close();
    header("Location:".queries("","",array()));
  }
}