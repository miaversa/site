<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/cart.php';

if (file_exists(__DIR__ . '/env.pro.php')) {
	require __DIR__ . '/env.pro.php';
} else {
	require __DIR__ . '/env.dev.php';
}