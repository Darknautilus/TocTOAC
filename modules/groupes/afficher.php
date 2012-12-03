<?php
/*
$gp = mysql_query(' Select * from groupes where idgroupe = ' + $idgroupe + ' ;');

$donnees= mysqli_fetch_assoc($gp);
*/
echo $twig->render("groupes_afficher.html", array());
