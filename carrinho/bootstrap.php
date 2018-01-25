<?php

$loader = new \Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new \Twig_Environment($loader, ['debug' => true, 'strict_variables' => true]);
