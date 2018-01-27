<?php

require __DIR__ . '/../bootstrap.php';

$cart = c_get();

$params = [
	'site' => $site,
	'cart' => $cart
];

$twig = getTemplates();
echo $twig->render('cart/index.html.twig', $params);
