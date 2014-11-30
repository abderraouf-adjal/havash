<?php

require_once('havash.php');


/* Make the hash and the salt (return array[Hash hex, Salt]) */
$hash_and_salt = havash_hash('GPp7jd6zJ2QJBJR8');


$passwd_hash = $hash_and_salt[0];
$salt = $hash_and_salt[1];


/* Check the password */
$havash_check_return = havash_check('GPp7jd6zJ2QJBJR8', $passwd_hash, $salt);

if ($havash_check_return === true) {
	echo 'Same password'.PHP_EOL;
}
else if ($havash_check_return === false) {
	echo 'NOT the same password'.PHP_EOL;
}
else {
	/* havash_check() Fail */
	exit(1);
}


echo 'The Hash: '.$passwd_hash.PHP_EOL;
echo 'The Salt: '.$salt.PHP_EOL;


?>
