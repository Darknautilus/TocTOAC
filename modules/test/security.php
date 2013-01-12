<?php

include(PATH_MODELES."/security.php");

$password = "root";

echo "Mot de passe en clair : ".$password."<br/>";

$hash = hash_password($password);

echo "Hash : ".$hash."<br/>";

echo "Test du mot de passe ".$password." avec check_password() : ".check_password($password, $hash);