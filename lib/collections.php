<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Collection;
use Miaversa\Statico\Type;

function collections() : array
{
	$collections = [];
	$file = CONTENT . '/site.json';
	$colarr = fjson($file);
	$colarr = $colarr['collections'];
	foreach($colarr as $c) {
		$collections[] = new Collection($c['slug'], $c['name'], $c['description']);
	}
	return $collections;
}

function render_index_collection(\Twig_Environment $template, Collection $collection, array $products, array $types, Type $type = null, array $order = null) : void
{
	$filename = "/produtos/{$collection->slug}";

	if( ! is_null($type)) {
		$filename .= "/{$type->slug}";
	}

	if( ! is_null($order)) {
		$filename .= "/{$order['slug']}";
	}

	$filename .= "/index.html";

	$content = $template->render('collection.html.twig', [
		'collection' => $collection,
		'products' => $products,
		'types' => $types,
		'type' => $type,
		'order' => $order
	]);
	put_file($filename, $content);
}

function render_collections(\Twig_Environment $template)
{
	$orders = [
		['slug' => 'mais-baratos'],
		['slug' => 'mais-caros'],
		['slug' => 'mais-novos'],
	];
	
	$all_types = types();
	$collections = collections();
	$all_products = products();

	foreach($collections as $c) {
		$products = filter_products_in_collection($c, $all_products);
		$types = filter_types_in_collection($c, $all_types, $all_products);

		render_index_collection($template, $c, $products, $types);
		foreach($orders as $order) {
			$p = sort_products($products, $order['slug']);
			render_index_collection($template, $c, $p, $types, null, $order);
		}
		
		foreach($types as $type) {
			$pint = filter_products_in_type($products, $type);
			render_index_collection($template, $c, $pint, $types, $type, null);
			foreach($orders as $order) {
				render_index_collection($template, $c, $pint, $types, $type, $order);
			}
		}
	}
}