<?php

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