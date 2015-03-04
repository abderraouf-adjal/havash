<?php

require_once('havash.php');

$test_password = 'GPp7jd6zJ2QJBJR8';

/* Make the hash and the salt (return array[Hash hex, Salt]) */
$hash_and_salt = havash_hash($test_password);
if ($hash_and_salt < 0) {
	goto fail;
}

$passwd_hash = $hash_and_salt[0];
$salt = $hash_and_salt[1];


/* Check the password */
$havash_check_return = havash_check($test_password, $passwd_hash, $salt);

if ($havash_check_return === true) {
	echo 'Same hash'.PHP_EOL;
}
else if ($havash_check_return === false) {
	echo 'NOT the same hash'.PHP_EOL;
}
else {
	/* havash_check() Fail */
	goto fail;
}


echo 'The Hash: '.$passwd_hash.PHP_EOL;
echo 'The Salt: '.$salt.PHP_EOL;

fail:
	exit(1);

?>
