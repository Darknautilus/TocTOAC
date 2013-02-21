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

echo "Liste des tables :<br/>\n";
$tables = $bdd->getTables();
foreach($tables as $table) {
  echo "*".$table."<br/>\n";
}

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\nExists :<br/>\n";
echo "Test : ".$bdd->exists("Test")."<br/>\n";
echo "test : ".$bdd->exists("test")."<br/>\n";
echo "Foo : ".$bdd->exists("Foo")."<br/>\n";
echo "Test.1 : ".($bdd->exists("Test", "idevent", 1)!=false)."<br/>\n";
echo "Test.100 : ".($bdd->exists("Test", "idevent", 100)!=false)."<br/>\n";

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\n== Tests sur table 'Test' ==<br/>\n";
echo "<br/>\nAffichage initial :<br/>\n";
$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\nInsert :";
$bdd->insert("Test", array("labelEvent" => "Event test 2", "dateEvent" => date("Y-m-j H:i:s")));

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\nUpdate :";
$bdd->update("Test", array("labelEvent" => "Event"), array("labelEvent" => "Event test 2"));

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\nDelete :";
$bdd->delete("Test", array("labelEvent" => "Event"));

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

$events = $bdd->select("SELECT * FROM Test");
echo afficher($events);

echo "<br/>\nErreurs : ";
echo $bdd->getLastError()."<br/>\n";

echo "<br/>\nResultat : ";
echo "<br/>\nErreurs : ";
print_r($bdd->close());