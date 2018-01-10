<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Site;

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


// TODO: incluir no autoload do composer
require __DIR__ . '/collections.php';