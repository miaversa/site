<?php

require __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/env.pro.php')) {
	require __DIR__ . '/env.pro.php';
} else {
	require __DIR__ . '/env.dev.php';
}

// ##############################################################
// FUNCTIONS
// ##############################################################

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

// ##############################################################
// CART
// ##############################################################

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
	if (! DEBUG) {
		$secure = true;
	}
	
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

// ##############################################################
// DB
// ##############################################################

function auth($email, $hash)
{
	$dynamo = new \Aws\DynamoDb\DynamoDbClient([
		'region' => 'sa-east-1',
		'version' => 'latest',
		'credentials' => [
			'key'    => AWS_KEY,
			'secret' => AWS_SECRET,
		],
	]);
	
	$u = $dynamo->getItem([
		'Key' => [
			'email' => [
			'S' => $email,
			]
		],
		'TableName' => 'users',
	]);

	if(is_null($u['Item'])) {
		return false;
	}

	if ($hash == $u['Item']['password']['S']) {
		return true;
	}

	return false;
}

function getUser($email)
{
	$dynamo = new \Aws\DynamoDb\DynamoDbClient([
		'region' => 'sa-east-1',
		'version' => 'latest',
		'credentials' => [
			'key'    => AWS_KEY,
			'secret' => AWS_SECRET,
		],
	]);

	$u = $dynamo->getItem([
		'Key' => [
			'email' => [
			'S' => $email,
			]
		],
		'TableName' => 'users',
	]);

	if(is_null($u['Item'])) {
		return null;
	}

	return $u['Item'];
}

function updateShippingData($email, $shipping)
{
	$dynamo = new \Aws\DynamoDb\DynamoDbClient([
		'region' => 'sa-east-1',
		'version' => 'latest',
		'credentials' => [
			'key'    => AWS_KEY,
			'secret' => AWS_SECRET,
		],
	]);

	$u = $dynamo->getItem([
		'Key' => [
			'email' => [
			'S' => $email,
			]
		],
		'TableName' => 'users',
	]);

	if(is_null($u['Item'])) {
		return false;
	}

	print '<pre>';
	print_r($u['Item']);

	$u['Item']['shipping'] = [
		'M' => [
			'address' => [
				'M' => [
					'number' => ['S' => $shipping['address']['number']],
					'country' => ['S' => $shipping['address']['country']],
					'city' => ['S' => $shipping['address']['city']],
					'street' => ['S' => $shipping['address']['street']],
					'district' => ['S' => $shipping['address']['district']],
					'postalCode' => ['S' => $shipping['address']['postalCode']],
					'complement' => ['S' => $shipping['address']['complement']],
				]
			]
		]
	];

	print_r($u['Item']);
	exit();
}

// ##############################################################
// LOGIN
// ##############################################################


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

// ##############################################################
// SHIPPING
// ##############################################################
function getShippingData()
{
	if('POST' == $_SERVER['REQUEST_METHOD']) {
		return getShippingDataFromRequest();
	}
	return getShippingDataFromDB();
}

function getShippingDataFromDB()
{
	$data = ['address' => [
		'street' => '',
		'number' => '',
		'complement' => '',
		'district' => '',
		'city' => '',
		'state' => '',
		'country' => '',
		'postalCode' => '',
	]];

	$email = s_get();
	$userData = getUser($email);

	if( (! isset($userData['shipping'])) || is_null($userData['shipping'])) {
		return $data;
	}

	$data = ['address' => [
		'street' => $userData['shipping']['M']['address']['M']['street']['S'],
		'number' => $userData['shipping']['M']['address']['M']['number']['S'],
		'complement' => $userData['shipping']['M']['address']['M']['complement']['S'],
		'district' => $userData['shipping']['M']['address']['M']['district']['S'],
		'city' => $userData['shipping']['M']['address']['M']['city']['S'],
		'state' => $userData['shipping']['M']['address']['M']['state']['S'],
		'country' => $userData['shipping']['M']['address']['M']['country']['S'],
		'postalCode' => $userData['shipping']['M']['address']['M']['postalCode']['S'],
	]];

	return $data;
}

function getShippingDataFromRequest()
{
	$data = ['address' => [
		'street' => '',
		'number' => '',
		'complement' => '',
		'district' => '',
		'city' => '',
		'state' => '',
		'country' => '',
		'postalCode' => '',
	]];

	if (isset($_POST['data']['address']['street'])) {
		$data['address']['street'] = trim($_POST['data']['address']['street']);
	}
	if (isset($_POST['data']['address']['number'])) {
		$data['address']['number'] = trim($_POST['data']['address']['number']);
	}
	if (isset($_POST['data']['address']['complement'])) {
		$data['address']['complement'] = trim($_POST['data']['address']['complement']);
	}
	if (isset($_POST['data']['address']['district'])) {
		$data['address']['district'] = trim($_POST['data']['address']['district']);
	}
	if (isset($_POST['data']['address']['city'])) {
		$data['address']['city'] = trim($_POST['data']['address']['city']);
	}
	if (isset($_POST['data']['address']['state'])) {
		$data['address']['state'] = trim($_POST['data']['address']['state']);
	}
	if (isset($_POST['data']['address']['country'])) {
		$data['address']['country'] = trim($_POST['data']['address']['country']);
	}
	if (isset($_POST['data']['address']['postalCode'])) {
		$data['address']['postalCode'] = trim($_POST['data']['address']['postalCode']);
	}

	return $data;
}