<?php

define('COOKIE_NAME', 'mcart');

function c_get()
{
	$cart = [];
	if (isset($_COOKIE[COOKIE_NAME])) {
		$cart = $_COOKIE[COOKIE_NAME];
		$cart = base64_decode($cart);
		$cart = json_decode($cart, true);
	}
	if (! isset($cart['items'])) {
		$cart['items'] = [];
	}
	return $cart;
}

function c_add($cart, $product)
{
	$cart['items'][] = [
		'product' => [
			'sku'   => $product['sku'],
			'name'  => $product['name'],
			'price' => $product['price'],
			'sign'  => $product['sign']
		]
	];
	return $cart;
}

function c_delete($cart, $idx)
{
	if(isset($cart['items'][$idx])) {
		unset($cart['items'][$idx]);
	}
	return $cart;
}

function c_update($cart)
{
	$content = json_encode($cart);
	$content = base64_encode($content);
	$secure = false;
	setcookie(COOKIE_NAME, $content, time()+60*60*24*30, '/', 'carrinho.miaversa.com.br', $secure, true);
}

function c_validate($product)
{
	if (! isset($product['sku'])) {
		return false;
	}

	if (! isset($product['name'])) {
		return false;
	}

	if (! isset($product['price'])) {
		return false;
	}

	$price = number_format($product['price'], 2, ',', '.');
	$sign = '#' . SALT . "#{$product['sku']}#{$product['name']}#{$price}#";
	$sign = sha1($sign);
	return $product['sign'] == $sign;
}
