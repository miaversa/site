<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (is_null($email)) {
	redirect('/login.php');
}

$data = [];

$params = [
	'site' => $site,
	'csrf' => sha1(date('H')),
	'form' => $data
];

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
