<?php

/*
 * havash version 0.1.0
 *
 * The MIT License (MIT)
 * Copyright (c) 2014 Abderraouf Adjal
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the “Software”), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */


/**
 * Return Values:
 *   On success: $salt
 *   On failure:
 *     (-1): No function to generate random bytes
 *     (-2): Random bytes generator function fail
 */

function mk_rand_salt($len)
{
	if (function_exists('mcrypt_create_iv')) {
		$salt = mcrypt_create_iv($len, MCRYPT_DEV_URANDOM);
	}
	else if (function_exists('openssl_random_pseudo_bytes')) {
		$salt = openssl_random_pseudo_bytes($len);
	}
	else {
		echo 'mk_rand_salt(): both openssl_random_pseudo_bytes() and mcrypt_create_iv() not exist,'.PHP_EOL
			.'mk_rand_salt() require one of them.'.PHP_EOL;
		
		return -1;
	}
	
	if (!($salt === false)) {
		return $salt;
	}
	else {
		return -2;
	}
}


/**
 * Return Values:
 *   On success: array(Hash in Hex, Salt)
 *   On failure:
 *     (-1): Fail to make the salt.
 *     (-2): Invalid $repeats value ($repeats < 0)
 */
 
function havash_hash($hash_func, $passwd, $repeats = 10000)
{
	$random_bytes_len = 32;
	$salt_hash_func = 'sha256';
	
	if ($repeats < 0) {
		echo 'havash_hash(): Invalid $repeats value ($repeats < 0)'.PHP_EOL;
		return -2;
	}
	
	$random_bytes = mk_rand_salt($random_bytes_len);
	if ($random_bytes < 0) {
		echo 'havash_hash(): Fail to make random bytes for salt.'.PHP_EOL;
		return -1;
	}
	else {
		$salt = hash($salt_hash_func, $random_bytes);
	}
	
	$h = hash_hmac($hash_func, $salt, $passwd, true);
	
	for ($i = 0; $i < $repeats; $i++) {
		$h = hash_hmac($hash_func, $h, $passwd, true);
	}
	
	return array(bin2hex($h), $salt);
}


/**
 * Return Values:
 *   On success:
 *     true : Hashs are the same
 *     false: Hashs are NOT the same
 *   On failure:
 *     (-2): Invalid $repeats value ($repeats < 0)
 */

function havash_check($hash_func, $passwd, $passwd_hash, $salt, $repeats = 10000)
{
	if ($repeats < 0) {
		echo 'havash_check(): Invalid $repeats value ($repeats < 0)'.PHP_EOL;
		return -2;
	}
	
	$h = hash_hmac($hash_func, $salt, $passwd, true);
	
	for ($i = 0; $i < $repeats; $i++) {
		$h = hash_hmac($hash_func, $h, $passwd, true);
	}
	
	return (bin2hex($h) === strtolower($passwd_hash));
}


?>
