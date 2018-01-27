<?php

require __DIR__ . '/../bootstrap.php';

$session = s_get();
if (is_null($session)) {
	redirect('/login.php');
}

$data = getShippingData();

if('POST' == $_SERVER['REQUEST_METHOD']) {
	//$data = getShippingData();
	print_r($_POST);
	exit();
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H')),
	'form' => $data
];

$twig = getTemplates();
echo $twig->render('cart/shipping.html.twig', $params);
