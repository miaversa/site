<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Collection;

function load_collections() : array
{
	$collections = [];

	$file = CONTENT . '/site.json';
	$colarr = fjson($file);
	$colarr = $colarr['collections'];
	foreach($colarr as $c)
	{
		$collections[] = new Collection($c['slug'], $c['name'], $c['description']);
	}

	return $collections;
}

function popular_products_collection($collection) : array
{
	$products = load_products();

	usort($products, function($a, $b) {
		if ($a->pop == $b->pop) {
			return 0;
		}
		return ($a->pop > $b->pop) ? -1 : 1;
	});

	return $products;
}

function render_index_collection(\Twig_Environment $template, $collection)
{
	$products = popular_products_collection($collection);
	
	$filename = OUTPUT . "/produtos/{$collection->slug}/index.html";
	$content = $template->render('collection.html.twig', [
		'collection' => $collection,
		'products' => $products,
	]);
	
	file_put_contents($filename, $content);
}

function render_collections(\Twig_Environment $template)
{
	$collections = load_collections();
	foreach($collections as $c) {
		render_index_collection($template, $c);
	}
}