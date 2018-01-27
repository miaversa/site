<?php

function getTemplates()
{
	$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
	$twig = new \Twig_Environment($loader, ['debug' => true, 'strict_variables' => true]);
	$twig->addGlobal('DEBUG', DEBUG);
	return $twig;
}

function redirect($location)
{
	header("Location: {$location}");
	exit();
}

function csrf_validation($token)
{
	$expected = sha1(date('H'));
	return $token == $expected;
}