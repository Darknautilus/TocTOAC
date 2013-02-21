<?php

$bdd = new BDD();
$tables = $bdd->getTables();
$bdd->close();

$GLOBALS["BASE_TABLES_NAMES"] = $tables;