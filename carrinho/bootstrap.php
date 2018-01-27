<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/cart.php';
require __DIR__ . '/db.php';
require __DIR__ . '/login.php';

if (file_exists(__DIR__ . '/env.pro.php')) {
	require __DIR__ . '/env.pro.php';
} else {
	require __DIR__ . '/env.dev.php';
}
