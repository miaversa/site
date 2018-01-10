<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Collection;
use Miaversa\Statico\Type;

function filter_products_in_collection(Collection $collection, array $all_products) : array
{
	$products = [];
	foreach($all_products as $product) {
		if ($product->collection == $collection->slug) {
			$products[] = $product;
		}
	}

	return $products;
}

function filter_products_in_type(array $all_products, Type $type) : array
{
	$products = [];
	foreach($all_products as $p) {
		if($p->type == $type->slug) {
			$products[] = $p;
		}
	}
	return $products;
}

function filter_types_in_collection(Collection $collection, array $alltypes, array $allproducts) : array
{
	$typeslugs = [];
	foreach($allproducts as $p) {
		if( ! in_array($p->type, $typeslugs)) {
			$typeslugs[] = $p->type;
		}
	}

	$types = [];
	$alltypes = types();
	foreach($alltypes as $dt) {
		if(in_array($dt->slug, $typeslugs)) {
			$types[] = $dt;
		}
	}

	return $types;
}
