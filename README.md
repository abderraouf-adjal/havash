havash
======

Simple Password Hashing Function in PHP

## Features

* Long random salt for every new hash to defend against dictionary attacks
	and against pre-computed rainbow table attacks.
* Slowing brute-force attacks (Configurable).
* Safe against length extension attacks.

---

Using [Scrypt](https://wikipedia.org/wiki/Scrypt) or [PBKDF2](https://wikipedia.org/wiki/PBKDF2) is recommended.
