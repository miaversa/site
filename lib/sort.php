<?php
declare(strict_types=1);

namespace Miaversa;

function sort_products(array $products, string $order) : array
{
	$funcs = [
		'mais-baratos' => function($a, $b) {
			if ($a->price == $b->price) {return 0;}
			return ($a->price < $b->price) ? -1 : 1;
		},
		'mais-caros' => function($a, $b) {
			if ($a->price == $b->price) {return 0;}
			return ($a->price > $b->price) ? -1 : 1;
		},
		'mais-novos' => function($a, $b) {
			if ($a->date == $b->date) {return 0;}
			return ($a->date < $b->date) ? -1 : 1;
		},
	];
	usort($products, $funcs[$order]);
	return $products;
}

function sort_posts(array $posts) : array
{
	usort($posts, function($a, $b) {
		if ($a->dateTime == $b->dateTime) {return 0;}
		return ($a->dateTime > $b->dateTime) ? -1 : 1;
	});
	return $posts;
}