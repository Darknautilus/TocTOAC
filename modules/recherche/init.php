<?php

// Ouverture de la connexion avec la BDD
$_SESSION["search"] = new BDD();

echo json_encode(array("result" => true));