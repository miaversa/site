<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/cart.php';

define('DEBUG', 1);

$salt = getenv('SALT');
if($salt == false) {
	echo "salt not set";
	exit();
}

define('SALT', $salt);

$site = [
	'siteurl' => 'http://miaversa.com.br:8080',
	'analytics' => 'UA-112427178-1'
];
