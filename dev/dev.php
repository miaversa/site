<?php
require 'carrinho/vendor/autoload.php';

function getID()
{
	$client = new \GuzzleHttp\Client();
	$options = [
		'form_params' => [
			'email' => 'daniela@miaversa.com.br',
			'token' => '9075CED20CB94F168B4F9BCC4953404D'
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


function transaction($hash)
{
	$client = new \GuzzleHttp\Client();

	$m = 'POST';
	$u = 'https://ws.pagseguro.uol.com.br/v2/transactions';
	$body = file_get_contents('doc.xml');
	$body = str_replace('-hash-', $hash, $body);

	$opt = [
		'headers' => ['Content-Type' => 'application/xml; charset=UTF-8'],
		'query' => [
			'email' => 'daniela@miaversa.com.br',
			'token' => '9D0575BB03814064B9B1EC61AE630BF8'
		],
		'body' => $body
	];
	
	try {
		$response = $client->request($m, $u, $opt);
	} catch(\GuzzleHttp\Exception\ClientException $e) {
		print_r($e->getResponse());
		print $e->getResponse()->getBody();
		return;
	}

	print_r($response);
	print $response->getBody();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	transaction($_POST['hash']);
}

?>
<html>
	<head>
		<title>teste</title>
		<script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
		<script>
			<?php $id = getID(); ?>
			PagSeguroDirectPayment.setSessionId('<?php echo $id ?>');

			function getPaymentSuccess(response) {
				console.log('getPaymentSuccess');
				console.log(response);
			}

			function getPaymentError(response) {
				console.log('getPaymentError');
				console.log(response);
			}

			function getPaymentComplete(response) {
				console.log('getPaymentComplete');
				console.log(response);
			}

			function getPaymentMethods() {
				PagSeguroDirectPayment.getPaymentMethods({
					amount: 500,
					success: getPaymentSuccess,
					error: getPaymentError,
					complete: getPaymentComplete
				});
			}

			function has() {
				h = PagSeguroDirectPayment.getSenderHash();
				document.getElementById("hhash").value = h;
				document.getElementById('xpay').submit();
			}
		</script>
	</head>
	<body>
		<h1>Teste</h1>
		<form id="xpay" method="post">
			<input id="hhash" type="hidden" name="hash" value="" />
		</form>
		<button onclick="has()">getPaymentMethods</button>
	</body>
</html>
