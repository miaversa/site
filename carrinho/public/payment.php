<?php

require __DIR__ . '/../bootstrap.php';

$session = s_get();
if (! is_null($session)) {
	redirect('/payment.php');
}

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
