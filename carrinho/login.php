<?php

define('SESSION_COOKIE_NAME', 'msession');

function s_get()
{
	$email = null;
	if (isset($_COOKIE[SESSION_COOKIE_NAME])) {
		$email = $_COOKIE[SESSION_COOKIE_NAME];
		$email = base64_decode($email);
	}
	return $email;
}

function s_set($email)
{
	$content = base64_encode($email);
	$secure = false;
	if (! DEBUG) {
		$secure = true;
	}
	setcookie(SESSION_COOKIE_NAME, $content, time()+60*60*24*30, '/', 'carrinho.miaversa.com.br', $secure, true);
}
