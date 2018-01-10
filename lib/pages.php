<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Page;

function page(string $file) : Page
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
	return new Page($meta['slug'], $meta['name'], $meta['description'], $content);
}

function render_page(\Twig_Environment $template, string $filename) : void
{
	$src = CONTENT . "/pages/{$filename}";
	$page = page($src);
	$dst = "/{$page->slug}/index.html";
	$content = $template->render('page.html.twig', ['page' => $page]);
	put_file($dst, $content);
}