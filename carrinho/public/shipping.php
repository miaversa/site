<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (is_null($email)) {
	redirect('/login.php');
}

$data = getShippingData();

if('POST' == $_SERVER['REQUEST_METHOD']) {
	if(updateShippingData($email, $data)) {
		redirect('/payment.php');
	}
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H')),
	'form' => $data
];

$twig = getTemplates();
echo $twig->render('cart/shipping.html.twig', $params);
