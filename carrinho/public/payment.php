<?php

require __DIR__ . '/../bootstrap.php';

if ( ! isset($_COOKIE['msession'])) {
	redirect('/login.php');
}

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
