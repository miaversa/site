<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Product;

function product(string $file) : Product
{
	$meta = '';
	$content = '';
	$contentStep = 0;
	$fileLines = file($file);
	foreach($fileLines as $line) {
		if('}' == substr($line, 0, 1)) {
			$meta .= $line;
			$contentStep++;
			continue;
		}
		if($contentStep >= 1) {
			$content .= $line;
		} else {
			$meta .= $line;
		}
	}
	$meta = json_decode($meta, true);

	$product = new Product;
	$product->sku = $meta['sku'];
	$product->name = $meta['name'];
	$product->slug = $meta['slug'];
	$product->type = $meta['type'];
	$product->collection = $meta['collection'];
	$date = \DateTime::createFromFormat('Y-m-d', $meta['date']);
	$product->date = $date;
	$product->price = $meta['price'];
	$product->description = $meta['description'];
	$product->pop = $meta['pop'];
	$product->content = $content;
	
	return $product;
}

function products() : array
{
	$products = [];

	$glog = CONTENT . '/products/*.html';
	$files = glob($glog);

	foreach($files as $file) {
		$products[] = product($file);
	}

	return $products;
}

function render_product(\Twig_Environment $template, Product $product) : void
{
	$filename = "/produtos/{$product->collection}/{$product->slug}/index.html";
	$content = $template->render('product.html.twig', ['product' => $product]);
	put_file($filename, $content);
}

function render_products(\Twig_Environment $template) : void
{
	$products = products();
	foreach($products as $product) {
		render_product($template, $product);
	}
}