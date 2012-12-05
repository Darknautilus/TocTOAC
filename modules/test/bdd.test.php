<?php

function afficher($table) {
	$out = "\n<table>";
	
	foreach ($table as $line) {
		$out .= "\n<tr>";
		foreach($line as $key => $value) {
			$out .= "\n\t<td>".$value."</td>";
		}
		$out.= "\n</tr>";
	}
	$out .= "\n</table>\n";
	
	return $out;
}

$bdd = new BDD();

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "\nInsert :";
$bdd->insert("Test", array("labelEvent" => "Event test 2", "dateEvent" => date("Y-m-j H:i:s")));

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "\nUpdate :";
$bdd->update("Test", array("labelEvent" => "Event"), array("labelEvent" => "Event test 2"));

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "\nDelete :";
$bdd->delete("Test", array("labelEvent" => "Event"));

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "Erreurs : ";
print_r($bdd->close());