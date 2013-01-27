<?php

if(isLogged()) {
  
  $bdd = new BDD();
  $valid = false;
  
  // On cherche les groupes dont le membre est le seul membre+
  $grpMbPlus = $GLOBALS["grpMbPlus"];
  $grpToCheckTemp = array();
  $grpToCheck = array();
  foreach ($grpMbPlus as $grp) {
    $mb = $bdd->select("select member from Own where grp = ".$grp.";");
    $mbPlus = $bdd->select("select member from Own where grp = ".$grp." and grnt = 2;");
    if(count($mb) > 1 && count($mbPlus) == 1)
      $grpToCheckTemp[] = $grp;
  }
  
  // On récupère les données des groupes
  if(!empty($grpToCheckTemp)) {
    foreach($grpToCheckTemp as $grp) {
      $grpComp = $bdd->select("select grpid, grpname, visibility, description, nbmemb, nbcat from Groups where grpid = ".$grp.";");
      $grpToCheck[] = $grpComp[0];
    }
  }
  
  if(empty($grpToCheck) && isset($_POST["filled"]) && isset($_POST["confirm"])) {
    // Suppression du membre
    // Suppression des groupes
    $bdd->delete("Own", array("member" => $GLOBALS["membinfos"]["membid"]));
    // Suppression des participations aux événements
    $bdd->delete("Participate", array("membre" => $GLOBALS["membinfos"]["membid"]));
    // Suppression du membre
    $bdd->delete("Members", array("membid" => $GLOBALS["membinfos"]["membid"]));
    
    // Destruction de la session et des cookies
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
      );
    }
    setcookie("logged", "", time()-3600);
    setcookie("membinfos", "", time()-3600);
    setcookie("grpMb", "", time()-3600);
    setcookie("grpMbPlus", "", time()-3600);
    session_destroy();
    
    majGlobals();
    
    $valid = true;
  }
    
  echo $twig->render("membres_desinscription.html", array("grpToCheck" => $grpToCheck, "valid" => $valid));
  
  $bdd->close();
}
else {
  echo $twig->render("index_show.html", array());
}