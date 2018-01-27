<?php

require __DIR__ . '/../bootstrap.php';

$product = null;
if (isset($_POST['product'])) {
	$product = $_POST['product'];
}

if(c_validate($product))
{
	$cart = c_get();
	$cart = c_add($cart, $product);
	c_update($cart);
}

header("Location: /");
