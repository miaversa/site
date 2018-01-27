<?php

require __DIR__ . '/../bootstrap.php';

$session = s_get();
if (is_null($session)) {
	redirect('/login.php');
}

if('POST' == $_SERVER['REQUEST_METHOD']) {
	$data = ['address' => [
		'street' => '',
		'number' => '',
		'complement' => '',
		'district' => '',
		'city' => '',
		'state' => '',
		'country' => '',
		'postalCode' => '',
	]];

	if (isset($_POST['data']['address']['street']) {
		$data['address']['street'] = $_POST['data']['address']['street'];
	}
	if (isset($_POST['data']['address']['number']) {
		$data['address']['number'] = $_POST['data']['address']['number'];
	}
	if (isset($_POST['data']['address']['complement']) {
		$data['address']['complement'] = $_POST['data']['address']['complement'];
	}
	if (isset($_POST['data']['address']['district']) {
		$data['address']['district'] = $_POST['data']['address']['district'];
	}
	if (isset($_POST['data']['address']['city']) {
		$data['address']['city'] = $_POST['data']['address']['city'];
	}
	if (isset($_POST['data']['address']['state']) {
		$data['address']['state'] = $_POST['data']['address']['state'];
	}
	if (isset($_POST['data']['address']['country']) {
		$data['address']['country'] = $_POST['data']['address']['country'];
	}
	if (isset($_POST['data']['address']['postalCode']) {
		$data['address']['postalCode'] = $_POST['data']['address']['postalCode'];
	}

	print '<pre>';
	print_r($data);
	exit();
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H'))
];

$twig = getTemplates();
echo $twig->render('cart/shipping.html.twig', $params);
