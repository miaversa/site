<?php

require __DIR__ . '/../bootstrap.php';

$email = s_get();
if (! is_null($email)) {
	redirect('/payment.php');
}


$params = [
	'site' => $site,
	'csrf' => sha1(date('H'))
];

$twig = getTemplates();
echo $twig->render('cart/register.html.twig', $params);
