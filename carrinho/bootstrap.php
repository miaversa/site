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

	$data = [
		'name' => $u['Item']['name']['S'],
		'email' => $u['Item']['email']['S'],
		'shipping' => [
			'address' => [
				'street' => $u['Item']['shipping']['M']['address']['M']['street']['S'],
				'number' => $u['Item']['shipping']['M']['address']['M']['number']['S'],
				'complement' => $u['Item']['shipping']['M']['address']['M']['complement']['S'],
				'district' => $u['Item']['shipping']['M']['address']['M']['district']['S'],
				'city' => $u['Item']['shipping']['M']['address']['M']['city']['S'],
				'state' => $u['Item']['shipping']['M']['address']['M']['state']['S'],
				'country' => $u['Item']['shipping']['M']['address']['M']['country']['S'],
				'postalCode' => $u['Item']['shipping']['M']['address']['M']['postalCode']['S'],
			]
		]
	];
	return $data;
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

	$u = $dynamo->getItem(['Key' => ['email' => ['S' => $email]], 'TableName' => 'users']);

	if(is_null($u['Item'])) {
		return false;
	}

	$u['Item']['shipping'] = [
		'M' => [
			'address' => [
				'M' => [
					'street' => ['S' => $shipping['address']['street']],
					'number' => ['S' => $shipping['address']['number']],
					'complement' => ['S' => $shipping['address']['complement']],
					'district' => ['S' => $shipping['address']['district']],
					'city' => ['S' => $shipping['address']['city']],
					'state' => ['S' => $shipping['address']['state']],
					'country' => ['S' => $shipping['address']['country']],
					'postalCode' => ['S' => $shipping['address']['postalCode']],
				]
			]
		]
	];

	$newItem = [
		'Key'       => ['email' => ['S' => $email]],
		'Item'      => $u['Item'],
		'TableName' => 'users',
	];

	$result = $dynamo->putItem($newItem);
	if ($result) {
		return true;
	}

	return false;
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
// REGISTER
// ##############################################################

function getRegisterData()
{
	$data = [
		'name' => '',
		'email' => '',
		'password' => ''
	];

	if('POST' == $_SERVER['REQUEST_METHOD']) {
		if (isset($_POST['data']['name'])) {
			$data['name'] = $_POST['data']['name']
		}
		if (isset($_POST['data']['email'])) {
			$data['email'] = $_POST['data']['email']
		}
		if (isset($_POST['data']['password'])) {
			$data['password'] = $_POST['data']['password']
		}
	}

	return $data;
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
		'street' => $userData['shipping']['address']['street'],
		'number' => $userData['shipping']['address']['number'],
		'complement' => $userData['shipping']['address']['complement'],
		'district' => $userData['shipping']['address']['district'],
		'city' => $userData['shipping']['address']['city'],
		'state' => $userData['shipping']['address']['state'],
		'country' => $userData['shipping']['address']['country'],
		'postalCode' => $userData['shipping']['address']['postalCode'],
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

// ##############################################################
// PAGSEGURO
// ##############################################################

function getIDPagseguro()
{
	$client = new \GuzzleHttp\Client();
	$options = [
		'form_params' => [
			'email' => PAGSEGURO_EMAIL,
			'token' => PAGSEGURO_TOKEN,
		]
	];
	$response = $client->post('https://ws.sandbox.pagseguro.uol.com.br/v2/sessions', $options);
	if (200 != $response->getStatusCode()) {
		print "erro ao obter id sessao\n";
		exit();
	}
	$body = $response->getBody();
	$start = strpos($body, '<id>');
	$end = strpos($body, '</id>');
	$id = substr($body, $start + 4, $end - $start - 4);
	return $id;
}

function boleto($hash)
{
	$email = s_get();
	$user = getUser($email);
	$cart = c_get();
	$params = ['hash' => $hash, 'user' => $user, 'cart' => $cart];
	$twig = getTemplates();
	$content = $twig->render('cart/boleto.xml.twig', $params);

	$urlx = 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/?email=' . PAGSEGURO_EMAIL . "&token=" . PAGSEGURO_TOKEN;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlx);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=UTF-8'));
	$data = curl_exec($ch);
	curl_close($ch);
	$data = simplexml_load_string($data);
	return $data;
}
