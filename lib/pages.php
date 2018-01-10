<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Page;

function load_page(string $file) : Page
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
	$page = load_page($src);
	$dst = OUTPUT . "/{$page->slug}/index.html";
	ensure_dir(dirname($dst));
	$content = $template->render('page.html.twig', ['page' => $page]);
	file_put_contents($dst, $content);
}