<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (is_null($email)) {
	redirect('/login.php');
}

$user = getUser($email);
if (!isset($user['shipping'])) {
	redirect('/shipping.php');
}

$twig = getTemplates();

if('POST' == $_SERVER['REQUEST_METHOD']) {
	$hash = '';
	if (isset($_POST['sender_hash'])) {
		$hash = $_POST['sender_hash'];
	}
	$response = boleto($hash);
	print_r($response);
	exit();
	$params = [
		'site' => $site,
		'paymentLink' => $response->paymentLink,
	];
	echo $twig->render('cart/boleto.html.twig', $params);
	exit();
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H')),
	'pagseguro_id' => getIDPagseguro()
];

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
