<?php

require __DIR__ . '/../bootstrap.php';

if('POST' == $_SERVER['REQUEST_METHOD']) {
	$csrf = '';
	$email = '';
	$password = '';

	if (isset($_POST['csrf'])) {
		$csrf = $_POST['csrf'];
	}

	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	}

	if (isset($_POST['password'])) {
		$password = $_POST['password'];
	}

	if (! csrf_validation($csrf)) {
		print 'csrf token error';
		exit();
	}

	$hash = sha1($password);

	if (auth($email, $hash)) {
		print 'auth ok';
	} else {
		print 'autho not';
	}
	exit();
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H'))
];

$twig = getTemplates();
echo $twig->render('cart/login.html.twig', $params);
