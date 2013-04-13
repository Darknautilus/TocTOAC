<?php

function hash_password($password){
	// 256 bits random string
	$salt = bin2hex(generateSalt(32));

	// prepend salt then hash
	$hash = hash("sha256", $password . $salt);

	// return salt and hash in the same string
	return $salt . $hash;
}

function check_password($password, $dbhash)
{
	// get salt from dbhash
	$salt = substr($dbhash, 0, 64);

	// get the SHA256 hash
	$valid_hash = substr($dbhash, 64, 64);

	// hash the password
	$test_hash = hash("sha256", $password . $salt);

	// test
	return $test_hash === $valid_hash;
}

function generateSalt($max = 15) {
  $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
  $i = 0;
  $salt = "";
  while ($i < $max) {
    $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
    $i++;
  }
  return $salt;
}