<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (! is_null($email)) {
	redirect('/payment.php');
}

$data = getRegisterData();

if('POST' == $_SERVER['REQUEST_METHOD']) {
	if (userRegister($data)) {
		s_set($data['email']);
		redirect('/login.php');
	}
}

$params = [
	'data' => $data,
	'site' => $site,
	'csrf' => sha1(date('H'))
];

$twig = getTemplates();
echo $twig->render('cart/register.html.twig', $params);
