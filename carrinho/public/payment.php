<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (is_null($email)) {
	redirect('/login.php');
}

$data = [];

if('POST' == $_SERVER['REQUEST_METHOD']) {
	$hash = '';
	if (isset($_POST['sender_hash'])) {
		$hash = $_POST['sender_hash'];
	}
	boleto($hash);
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H')),
	'form' => $data,
	'pagseguro_id' => getIDPagseguro()
];

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
