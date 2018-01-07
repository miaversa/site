<?php
declare(strict_types=1);

namespace Miaversa;

function ensure_dir(string $dir) : void
{
	if ( ! is_dir($dir)) {
		mkdir($dir, 0777, true);
	}
}

// json file to array
function fjson(string $filename) : array
{
	$content = file_get_contents($filename);
	return json_decode($content, true);
}

function get_renderer(string $templates) : \Twig_Environment
{
	$loader = new \Twig_Loader_Filesystem(ROOT . $templates);
	$twig = new \Twig_Environment($loader, ['debug' => true, 'strict_variables' => true]);
	return $twig;
}

function get_site(string $file) : Site
{
	$a = fjson(CONTENT . $file);
	return new Site($a['name'], $a['baseURL'], $a['description'], $a['twitter']);
}

function load_page(string $file) : Page
{
	$meta = [];
	$content = '';
	$contentStep = 0;
	$fileLines = file($file);

	foreach($fileLines as $line) {
		if('#' == substr($line, 0, 1)) {
			$contentStep++;
			continue;
		}

		if($contentStep >= 1) {
			$content .= $line;
		} else {
			$line = trim($line);
			$m = explode('=', $line);
			$meta[trim($m[0])] = trim($m[1]);
		}
	}

	return new Page($meta['slug'], $meta['name'], $meta['description'], $content);
}

function render_page($template, $src, $dst) : void
{
	$src = CONTENT . $src;
	$dst = OUTPUT . $dst;

	$page = load_page($src);
	$content = $template->render('page.html.twig', ['page' => $page]);
	file_put_contents($dst, $content);
}