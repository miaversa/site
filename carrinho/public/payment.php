<?php

require __DIR__ . '/../bootstrap.php';

$twig = getTemplates();
echo $twig->render('cart/payment.html.twig', $params);
