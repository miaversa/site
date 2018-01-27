<?php

require __DIR__ . '/../bootstrap.php';

$session = s_get();
if (is_null($session)) {
	redirect('/login.php');
}

$params = [
	'site' => $site,
	'csrf' => sha1(date('H'))
];

$twig = getTemplates();
echo $twig->render('cart/shipping.html.twig', $params);
