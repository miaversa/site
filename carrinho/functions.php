<?php

use \Dflydev\FigCookies\FigRequestCookies;

function getRequest() {
	$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
	return $request;
}

function getSession($request) {
	$default = json_encode(['cart' => ['items' => [], 'total' => 102.3], 'email' => null]);
	$cookie = FigRequestCookies::get($request, 'miaversa', $default);
	return json_decode($cookie->getValue(), true);
}

function getSite() {
	$content = file_get_contents(dirname(__DIR__) . '/content/site.json');
	return json_decode($content);
}

function getTemplates() {
	$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
	$twig = new \Twig_Environment($loader, ['debug' => true, 'strict_variables' => true]);
	$twig->addGlobal('DEBUG', DEBUG);
	return $twig;
}
