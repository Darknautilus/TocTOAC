<?php

/*
 * Classe de connexion Ã  la base de donnÃ©es
 * Utilise PDO (veillez Ã  bien l'activer sur le serveur !)
 */

class BDD {
	
private $errors = array();
private $bdd = NULL;
private $lastError;

/*
 * Constructeur de la classe :
 * crÃ©e l'objet de connexion Ã  la base
 */
function __construct() {
	try {
		$this->bdd = new PDO(DBHEADER, DBUSER, DBPASSWD);
	}
	catch (PDOException $e) {
		$this->errors[] = $e->getMessage();
		$this->bdd = NULL;
	}
}

function getLastError() {
	return $this->lastError;
}

/*
 * Ferme la connexion et retourne les Ã©ventuelles erreurs survenues
 */
function close() {
	$this->bdd = NULL;
	return $this->errors;
}
	
/*
 * Pour faire un SELECT sur la table.
 * ParamÃ¨tres :
 * 		$requete : une requÃªte SQL classique
 * 
 * Retourne le tableau des rÃ©sultats. Chaque enregistrement est un Ã©lÃ©ment du tableau et est prÃ©sentÃ© sous la forme d'un tableau associatif de la forme champ => valeur. 
 */
function select ($requete) {
	try {
		$result = $this->bdd->query($requete);
		$lines = array();
		while($line = $result->fetch(PDO::FETCH_ASSOC)) {
			$lines[] = $line;
		}
		return $lines;
	}
	catch (PDOException $e) {
		$this->lastError = $e->getMessage();
		$this->errors[] = $this->lastError;
		return false;
	}
}

/*
 * Effectue un update sur une table.
 * ParamÃ¨tres :
 * 		$table : la table oÃ¹ effectuer l'update
 * 		$colonnes : tableau associatif de la forme champ => valeur
 * 		$conditions : idem, conditions aprÃ¨s le WHERE
 * 
 * Retourne true si l'update s'est fait correctement, et false sinon
 */
function update ($table, $colonnes, $conditions) {
	
	$colonnes_ = array() ;
	foreach($colonnes as $colonne => $valeur) {
		if (!is_numeric($valeur)) {
			$valeur = $this->bdd->quote($valeur) ;
		}
		$colonnes_[] = "$colonne = $valeur" ;
	}
 
	$conditions_ = array() ;
	foreach($conditions as $condition => $valeur) {
		if (!is_numeric($valeur)) {
			$valeur = $this->bdd->quote($valeur) ;
		}
		$conditions_[] = "$condition = $valeur" ;
	}
 
	$sql = "UPDATE $table SET " ;
	$sql .= join(', ', $colonnes_) ;
	$sql .= ' WHERE ' . join(' AND ', $conditions_) ;
	
	var_dump($sql);
 
	try {
		$resultat = $this->bdd->exec($sql);
		return true;
	}
	catch (PDOException $e) {
		$this->lastError = $e->getMessage();
		$this->errors[] = $this->lastError;
		return false;
	}
}

/*
 * Supprime un enregistrement de la table
 * ParamÃ¨tres :
 * 		$table : la table oÃ¹ supprimer l'enregistrement
 * 		$conditions : tableau associatif des conditions aprÃ¨s le WHERE
 * 
 * Retourne true si la suppression s'est faite correctement, et false sinon
 */
function delete ($table, $conditions) {
	$conditions_ = array() ;
	foreach($conditions as $condition => $valeur) {
		if (!is_numeric($valeur)) {
			$valeur = $this->bdd->quote($valeur) ;
		}
		$conditions_[] = "$condition = $valeur" ;
	}
 
	$sql = "DELETE FROM $table WHERE " . join(' AND ', $conditions_) ;
	
	var_dump($sql);
	
	try {
		$resultat = $this->bdd->exec($sql) ;
		return true ;
	}
	catch (PDOException $e) {
		$this->lastError = $e->getMessage();
		$this->errors[] = $this->lastError;
		return false;
	}
}
 
/*
 * InsÃ¨re un Ã©lÃ©ment dans la base
 * ParamÃ¨tres :
 * 		$table : la table oÃ¹ insÃ©rer l'enregistrement
 * 		$valeurs : tableau associatif de la forme champ => valeur
 * 
 * Retourne true si la suppression s'est faite correctement, et false sinon
 */
function insert ($table, $valeurs) {
	$colonnes_ = array_keys($valeurs) ;
	$valeurs_ = array_values($valeurs) ;
	foreach($valeurs_ as $clef => $valeur) {
		if (!is_numeric($valeur)) {
			$valeur = $this->bdd->quote($valeur) ;
		}
		$valeurs_[$clef] = $valeur ;
	}
 
	$sql = "INSERT INTO $table (" ;
	$sql .= join(', ', $colonnes_) ;
	$sql .= ') VALUES (' ;
	$sql .= join(', ', $valeurs_) ;
	$sql .= ');' ;
	
	var_dump($sql);
 
	try {
		$resultat = $this->bdd->exec($sql);
		return $resultat;
	}
	catch (PDOException $e) {
		$this->lastError = $e->getMessage();
		$this->errors[] = $this->lastError;
		return false;
	}
}
	
}