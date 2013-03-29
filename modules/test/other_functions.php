<?php

$bdd = new BDD();

echo "Test des fonctions de controle d'appartenance a un groupe<br/>\n";
// Récupération de l'id du membre de test
$id = $bdd->select("select membid from Members where membmail = 'aure_bertron@hotmail.com';");
$id = $id[0]["membid"];
$idgrp = 1;
// Ajout d'un groupe à l'utilisateur de test en tant que membre
$bdd->insert("Own", array("grp" => $idgrp, "member" => $id, "grnt" => 1));
if(isMb($idgrp, $id)) {
  echo "OK : membre dans groupe (membre)<br/>\n";
}
else {
  echo "NOK : non membre dans groupe (membre)\n";
}
$bdd->delete("Own", array("member" => $id));
// Ajout d'un groupe à l'utilisateur de test en tant que membre plus
$bdd->insert("Own", array("grp" => $idgrp, "member" => $id, "grnt" => 2));
if(isMbPlus($idgrp, $id)) {
  echo "OK : membre dans groupe (membre plus)<br/>\n";
}
else {
  echo "NOK : non membre dans groupe (membre plus)\n";
}
$bdd->delete("Own", array("member" => $id));