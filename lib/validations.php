<?php
declare(strict_types=1);

namespace Miaversa;

function validate_images() : void
{
	$products = products();

	foreach($products as $product) {
		$filename = CONTENT . "/images/1200x630/{$product->slug}.jpg";
		if ( ! file_exists($filename)) {
			print "image validation: image 1200x630 for {$product->slug} not found\n";
		}

		$size = filesize($filename);
		$size /= 1024;
		if ($size > 900) {
			print "image validation: image 1200x630 for {$product->slug} bigger than 900kb. current size: {$size}\n";
		}

		$filename = CONTENT . "/images/2600x1300/{$product->slug}.jpg";
		if ( ! file_exists($filename)) {
			print "image validation: image 2600x1300 for {$product->slug} not found\n";
		}

		$size = filesize($filename);
		$size /= 1024;
		$size /= 1024;
		if ($size > 3.0) {
			print "image validation: image 2600x1300 for {$product->slug} bigger than 3Mb. current size: {$size}\n";
		}
	}
}

function validate_posts() : void
{
	$posts = posts();
	foreach($posts as $post) {

		$len = strlen($post->title);
		if ($len < 10 || $len > 55) {
			print "post validation: {$post->slug} title < 10 or > 55\n";
		}

		$len = strlen($post->description);
		if ($len < 50 || $len > 300) {
			print "post validation: {$post->slug} description with description < 50 or > 300\n";
		}

		$summarylen = strlen($post->summary);
		if ($summarylen < 500) {
			print "post validation: {$post->slug} with summary < 500\n";
		}

		$contentlen = strlen($post->content);
		if ($contentlen < 800) {
			print "post validation: {$post->slug} with content < 800\n";
		}
	}
}

function validate_products() : void
{
	$products = products();
	foreach($products as $product) {
		$len = strlen($product->description);

		if ($len < 50 || $len > 300) {
			print "product validation: product {$product->slug} with description < 50 or > 300\n";
		}
	}

	foreach($products as $a) {
		foreach($products as $b) {
			if ($a->slug == $b->slug) {continue;}
			$lev = levenshtein($a->description, $b->description);
			if ($lev < 7) {
				print "product validation: {$a->slug} and {$b->name} with levenshtein = {$lev}\n";
			}
		}
	}
}

function validate() : void
{
	validate_images();
	validate_posts();
	validate_products();
}