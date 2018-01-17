<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Site;

function ensure_dir(string $dir) : void {
	if ( ! is_dir($dir)) {
		mkdir($dir, 0777, true);
	}
}

function put_file(string $filename, string $content) : void {
	$filename = OUTPUT . $filename;
	ensure_dir(dirname($filename));
	file_put_contents($filename, $content);
}

function fjson(string $filename) : array {
	$content = file_get_contents($filename);
	return json_decode($content, true);
}

function get_renderer(string $templates) : \Twig_Environment {
	$loader = new \Twig_Loader_Filesystem(ROOT . $templates);
	$twig = new \Twig_Environment($loader, ['debug' => true, 'strict_variables' => true]);
	return $twig;
}

function get_salt() : string {
	return getenv('SALT');
}

function get_site() : Site {
	$a = fjson(CONTENT . '/site.json');
	return new Site($a['name'], $a['baseURL'], $a['cartURL'], $a['description'], $a['twitter'], $a['analytics']);
}

// TODO: remover
require __DIR__ . '/style.php';
