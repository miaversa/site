<?php

require __DIR__ . '/../bootstrap.php';

$idx = '';
if (isset($_POST['idx'])) {
	$idx = $_POST['idx'];
}

$cart = c_get();
$cart = c_delete($cart, $idx);
c_update($cart);

header("Location: /");