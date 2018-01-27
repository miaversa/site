<?php

function auth($email, $password)
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

	var_dump($u);
	exit();
}