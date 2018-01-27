<?php

function auth($email, $password)
{
	$dynamo = new \Aws\DynamoDb\DynamoDbClient(['region' => 'sa-east-1', 'version' => 'latest']);
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