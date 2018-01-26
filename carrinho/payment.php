<?php

require __DIR__ . '/../bootstrap.php';

$twig = getTemplates();
echo $twig->render('cart/index.html.twig', $params);
